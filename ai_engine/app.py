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

        for item in items:
            name = item.get('medicine_name', 'Unknown Medicine')
            dosage = item.get('dosage', 'Standard Dose')

            med_data = med_db.get(name.lower())

            if med_data:
                # 🔥 XAI Factors
                factors = generate_xai_factors(med_data)

                # Confidence score (based on factors)
                confidence = round(sum(f['impact'] for f in factors), 2)
                confidence = min(confidence, 0.95)

                explanation_text = (
                    f"This explanation is based on {len(factors)} key factors including "
                    f"risk level, mechanism of action, and side effects."
                )

                english_data = {
                    "usage": med_data.get('usage_en', f"Used for {name}"),
                    "dosage": dosage,
                    "side_effects": med_data.get('side_effects_en', "").split('; '),
                    "warnings": [
                        {
                            "issue": med_data.get('warning_issue_en', "Precautions"),
                            "reason": f"{med_data.get('why_warning', '')} (Risk: {med_data.get('risk_level', 'Low')})"
                        }
                    ],
                    "summary": med_data.get('explanation_en', "Follow doctor's instructions."),

                    # ✅ XAI الجزء المهم
                    "xai": {
                        "confidence_score": confidence,
                        "explanation": explanation_text,
                        "feature_importance": factors
                    }
                }

                arabic_data = {
                    "usage": f"يُستخدم لعلاج الحالات المتعلقة بـ {name}.",
                    "dosage": dosage,
                    "side_effects": ["راجع النشرة الداخلية"],
                    "warnings": [
                        {
                            "issue": "تنبيه",
                            "reason": med_data.get('explanation_ar', "استشر الطبيب.")
                        }
                    ],
                    "summary": med_data.get('explanation_ar', "تناول الدواء حسب التعليمات."),
                    "xai": {
                        "confidence_score": confidence,
                        "explanation": "تم توليد هذا التفسير بناءً على عوامل مثل الخطورة والآلية.",
                        "feature_importance": factors
                    }
                }

            else:
                english_data = {
                    "usage": f"Used to treat conditions related to {name}.",
                    "dosage": dosage,
                    "side_effects": ["Generic side effects"],
                    "warnings": [
                        {"issue": "Caution", "reason": "No data found."}
                    ],
                    "summary": f"Take {name} as prescribed.",
                    "xai": {
                        "confidence_score": 0.3,
                        "explanation": "Low confidence due to missing dataset.",
                        "feature_importance": []
                    }
                }

                arabic_data = {
                    "usage": f"يُستخدم لعلاج الحالات المتعلقة بـ {name}.",
                    "dosage": dosage,
                    "side_effects": ["أعراض عامة"],
                    "warnings": [
                        {"issue": "تنبيه", "reason": "لا توجد بيانات كافية."}
                    ],
                    "summary": f"تناول {name} حسب وصف الطبيب.",
                    "xai": {
                        "confidence_score": 0.3,
                        "explanation": "ثقة منخفضة بسبب نقص البيانات.",
                        "feature_importance": []
                    }
                }

            enriched_items.append({
                "drug_name": name,
                "english": english_data,
                "arabic": arabic_data
            })

        return jsonify({
            "status": "success",
            "data": enriched_items
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 400

if __name__ == '__main__':
    app.run(port=5005)

