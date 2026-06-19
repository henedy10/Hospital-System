# pyrefly: ignore [missing-import]
from flask import Flask, request, jsonify
from flask_cors import CORS
# pyrefly: ignore [missing-import]
import joblib
import pandas as pd
import os
import csv
from sklearn.linear_model import LinearRegression
# pyrefly: ignore [missing-import]
import numpy as np
import random

app = Flask(__name__)
CORS(app)

# Change directory to the script's location
os.chdir(os.path.dirname(os.path.abspath(__file__)))

# Load the model
try:
    model = joblib.load('symptom_model.joblib')
except FileNotFoundError:
    model = None

# Enhanced Mapping
MAPPING = {
    "Flu": {"specialization": "Internal Medicine", "urgency": "medium"},
    "Cold": {"specialization": "General Medicine", "urgency": "low"},
    "Heart Condition": {"specialization": "Cardiology", "urgency": "high"},
    "Pneumonia": {"specialization": "Pulmonology", "urgency": "high"},
    "Critical Condition": {"specialization": "Emergency Medicine", "urgency": "high"},
    "Symptomatic Fever": {"specialization": "General Medicine", "urgency": "medium"},
    "Migraine": {"specialization": "Neurology", "urgency": "medium"},
    "Headache": {"specialization": "General Medicine", "urgency": "low"},
    "Fatigue": {"specialization": "Internal Medicine", "urgency": "low"},
    "Anxiety": {"specialization": "Psychiatry", "urgency": "low"},
    "Stomach Flu": {"specialization": "Gastroenterology", "urgency": "medium"},
    "Sore Throat Infection": {"specialization": "ENT", "urgency": "low"},
    "Vertigo": {"specialization": "Neurology", "urgency": "medium"},
    "Dizziness": {"specialization": "General Medicine", "urgency": "low"},
    "Nausea": {"specialization": "General Medicine", "urgency": "low"},
    "Cardiac Stress": {"specialization": "Cardiology", "urgency": "high"},
    "Sepsis": {"specialization": "Emergency Medicine", "urgency": "high"},
    "Bronchitis": {"specialization": "Pulmonology", "urgency": "medium"},
    "Common Cold": {"specialization": "General Medicine", "urgency": "low"},
    "Tonsillitis": {"specialization": "ENT", "urgency": "medium"},
    "Food Poisoning": {"specialization": "Gastroenterology", "urgency": "medium"},
    "Gastroenteritis": {"specialization": "Gastroenterology", "urgency": "medium"}
}

@app.route('/predict', methods=['POST'])
def predict():
    if model is None:
        return jsonify({"error": "Model not trained"}), 500
    
    data = request.json
    try:
        # Features including new symptoms
        feature_keys = [
            'fever', 'cough', 'headache', 'fatigue', 'chest_pain',
            'shortness_of_breath', 'dizziness', 'nausea', 'sore_throat'
        ]
        
        features = [data.get(key, 0) for key in feature_keys]
        
        # Make prediction using DataFrame to avoid feature name warnings
        features_df = pd.DataFrame([features], columns=feature_keys)
        prediction = model.predict(features_df)[0]
        
        # Get mapping
        mapping = MAPPING.get(prediction, {"specialization": "General Medicine", "urgency": "medium"}).copy()
        
        # Improved Urgency Logic (Composite Rules)
        urgency = mapping['urgency']
        
        # Rule 1: Chest Pain + Shortness of Breath = HIGH (regardless of prediction)
        if data.get('chest_pain') == 1 and data.get('shortness_of_breath') == 1:
            urgency = 'high'
            mapping['specialization'] = 'Cardiology / Emergency'
            
        # Rule 2: Chest Pain alone = HIGH
        elif data.get('chest_pain') == 1:
            urgency = 'high'
            mapping['specialization'] = 'Cardiology'
            
        # Rule 3: Shortness of breath alone = HIGH
        elif data.get('shortness_of_breath') == 1:
            urgency = 'high'
            mapping['specialization'] = 'Pulmonology'

        # Rule 4: High Fever + Nausea + Fatigue = MEDIUM (Potential infection)
        elif data.get('fever') == 1 and data.get('nausea') == 1:
            urgency = 'medium'

        return jsonify({
            "predicted_disease": prediction,
            "specialization": mapping['specialization'],
            "urgency": urgency
        })
        
    except Exception as e:
        return jsonify({"error": str(e)}), 400


@app.route('/predict-load', methods=['POST'])
def predict_load():
    data = request.json.get('data', [])
    if not data or len(data) < 2:
        return jsonify({"error": "Insufficient data for prediction. Need at least 2 points."}), 400
    
    try:
        # Prepare data for Linear Regression
        X = np.array(range(len(data))).reshape(-1, 1)
        y = np.array(data)
        
        # Train model
        model_lr = LinearRegression()
        model_lr.fit(X, y)
        
        # Predict next value (next month)
        next_step = np.array([[len(data)]])
        prediction = model_lr.predict(next_step)
        
        return jsonify({
            "next_month_prediction": int(round(prediction[0]))
        })
    except Exception as e:
        return jsonify({"error": str(e)}), 400


# --------------------------------------------------------------------------
# AI Prescription Explainer Endpoint
# --------------------------------------------------------------------------

# Helper: Generate fake SHAP-like importance
def generate_xai_factors(med_data):
    factors = []

    # Example factors (simulate XAI reasoning)
    if med_data.get('side_effects_en'):
        factors.append({
            "feature": "Side Effects Severity",
            "impact": round(random.uniform(0.2, 0.5), 2),
            "description": "This drug has noticeable side effects that affect explanation confidence."
        })

    if med_data.get('risk_level', '').lower() == 'high':
        factors.append({
            "feature": "Risk Level",
            "impact": round(random.uniform(0.4, 0.7), 2),
            "description": "High risk level increases importance in explanation."
        })

    if med_data.get('mechanism_of_action'):
        factors.append({
            "feature": "Mechanism of Action",
            "impact": round(random.uniform(0.3, 0.6), 2),
            "description": "Mechanism clarity improves interpretability."
        })

    return factors


@app.route('/xai-explain-prescription', methods=['POST'])
def xai_explain_prescription():
    try:
        data = request.json or {}
        items = data.get('items', [])
        
        # Load dataset
        med_db = {}
        with open('medications_dataset.csv', mode='r', encoding='utf-8') as f:
            reader = csv.DictReader(f)
            for row in reader:
                med_db[row['name'].lower()] = row

        enriched_items = []
        all_drugs_data = []

        # First pass: collect all medication data
        for item in items:
            name = item.get('medicine_name', 'Unknown')
            med_data = med_db.get(name.lower())
            if med_data:
                all_drugs_data.append(med_data)

        # Overall diagnosis and treatment goal (from first drug or generic)
        overall_diagnosis = all_drugs_data[0].get('usage_en', 'Hypertension') if all_drugs_data else "General Condition"
        overall_treatment_goal = all_drugs_data[0].get('treatment_goal_en', 'Lower blood pressure and stabilize condition.') if all_drugs_data else "Improve health outcomes."
        
        # Synergies (Venn Diagram)
        synergies = []
        if len(all_drugs_data) >= 2:
            synergies = [{
                "drug_a": all_drugs_data[0]['name'],
                "drug_b": all_drugs_data[1]['name'],
                "mechanism_a": all_drugs_data[0].get('drug_class', 'Support'),
                "mechanism_b": all_drugs_data[1].get('drug_class', 'Support'),
                "benefit": "Combining these therapies targets multiple disease pathways simultaneously."
            }]

        # Top Factors (for bar chart)
        top_factors = [
            {"factor": "High blood pressure diagnosis", "weight": 0.40},
            {"factor": "Age and risk factors", "weight": 0.20},
            {"factor": "Medication effectiveness", "weight": 0.20},
            {"factor": "Safe drug combination", "weight": 0.15},
            {"factor": "Clinical guidelines", "weight": 0.05}
        ]

        for item in items:
            name = item.get('medicine_name', 'Unknown Medicine')
            dosage = item.get('dosage', 'Standard Dose')
            duration = item.get('duration', '90 days')
            frequency = item.get('frequency', '1 time per day')
            instructions = item.get('instructions', 'Take as directed')

            med_data = med_db.get(name.lower())

            if med_data:
                # Dynamic Importance (simulate)
                importance = 0.88 if "lisinopril" in name.lower() else 0.76
                if len(items) == 1: importance = 1.0

                english_data = {
                    "drug_class": med_data.get('drug_class', 'General'),
                    "usage": med_data.get('how_it_works_en', "Mechanism of action varies."),
                    "summary": med_data.get('why_prescribed_en', "Recommended based on your current health status."),
                    "how_it_works": med_data.get('how_it_works_en', "Mechanism of action varies."),
                    "why_prescribed": med_data.get('why_prescribed_en', "Recommended based on your current health status."),
                    "importance": importance,
                    "dosage": dosage,
                    "frequency": frequency,
                    "duration": duration,
                    "instructions": instructions,
                    "side_effects": str(med_data.get('side_effects_en') or "").split('; ') if med_data.get('side_effects_en') else ["General side effects"],
                    "warnings": str(med_data.get('warnings_en') or "Follow clinical instructions; Consult healthcare provider").split('; '),
                    "lifestyle": str(med_data.get('lifestyle_recommendations_en') or "Healthy diet; Regular Exercise").split('; '),
                    "xai": {
                        "confidence_score": 0.94,
                        "feature_importance": [
                            {"feature": "Clinical Guidelines", "impact": 0.45},
                            {"feature": "Disease Severity", "impact": 0.30},
                            {"feature": "Drug Synergy", "impact": 0.15},
                            {"feature": "Patient History", "impact": 0.10}
                        ]
                    }
                }

                arabic_data = {
                    "drug_class": "فئة الدواء",
                    "usage": med_data.get('explanation_ar', "آلية العمل."),
                    "summary": "محسن لعلاج حالتك الصحية.",
                    "importance": importance,
                    "dosage": dosage,
                    "frequency": frequency,
                    "duration": duration,
                    "instructions": instructions,
                    "side_effects": ["تحقق من العبوة"],
                    "warnings": ["اتبع التعليمات"],
                    "lifestyle": ["نظام غذائي صحي"],
                    "xai": {
                        "confidence_score": 0.94,
                        "feature_importance": [
                            {"feature": "المبادئ التوجيهية", "impact": 0.45},
                            {"feature": "خطورة المرض", "impact": 0.30},
                            {"feature": "تآزر الأدوية", "impact": 0.15},
                            {"feature": "تاريخ المريض", "impact": 0.10}
                        ]
                    }
                }
            else:
                english_data = {
                    "drug_class": "Generic",
                    "usage": f"Standard action for {name}.",
                    "summary": "General treatment.",
                    "importance": 0.5,
                    "dosage": dosage,
                    "frequency": frequency,
                    "duration": duration,
                    "instructions": instructions,
                    "side_effects": ["General side effects"],
                    "warnings": ["Caution"],
                    "lifestyle": ["Stay healthy"],
                    "xai": {
                        "confidence": 0.3,
                        "feature_importance": [{"feature": "Generic Match", "impact": 1.0}]
                    }
                }
                arabic_data = {
                    "drug_class": "دواء عام",
                    "usage": f"عمل قياسي لـ {name}.",
                    "why_prescribed": "علاج عام.",
                    "importance": 0.5,
                    "dosage": dosage,
                    "frequency": frequency,
                    "duration": duration,
                    "instructions": instructions,
                    "side_effects": ["أعراض عامة"],
                    "warnings": ["تنبيه"],
                    "lifestyle": ["حافظ على صحتك"],
                    "xai": {"confidence_score": 0.3, "explanation": "بيانات عامة."}
                }

            enriched_items.append({
                "drug_name": name,
                "english": english_data,
                "arabic": arabic_data
            })

        return jsonify({
            "status": "success",
            "overall_diagnosis": overall_diagnosis,
            "overall_treatment_goal": overall_treatment_goal,
            "overall_confidence": 0.92,
            "top_factors": top_factors,
            "synergies": synergies,
            "data": enriched_items
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 400

if __name__ == '__main__':
    app.run(port=5005)

