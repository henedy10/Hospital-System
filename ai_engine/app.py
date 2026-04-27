from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import pandas as pd
import os
from sklearn.linear_model import LinearRegression
import numpy as np

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
        
        # Make prediction
        prediction = model.predict([features])[0]
        
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

if __name__ == '__main__':
    app.run(port=5005)
