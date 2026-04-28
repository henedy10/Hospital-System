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

# Arabic medicine knowledge base (mirrored from Laravel service)
MEDICINE_KB = {
    'paracetamol':   'مسكن للألم وخافض للحرارة يُستخدم لعلاج الألم الخفيف إلى المتوسط والحمى',
    'acetaminophen': 'مسكن للألم وخافض للحرارة',
    'ibuprofen':     'مضاد للالتهاب غير ستيرويدي، يُستخدم لتخفيف الألم والالتهاب والحمى',
    'amoxicillin':   'مضاد حيوي من مجموعة البنسلين لعلاج العدوى البكتيرية',
    'azithromycin':  'مضاد حيوي من مجموعة الماكروليد لعلاج التهابات الجهاز التنفسي',
    'omeprazole':    'مثبط لمضخة البروتون يُستخدم لعلاج قرحة المعدة والحموضة',
    'metformin':     'دواء سكري من النوع الثاني يُساعد على خفض مستوى السكر في الدم',
    'amlodipine':    'مُوسِّع للأوعية الدموية لعلاج ضغط الدم المرتفع وذبحة صدرية',
    'atorvastatin':  'دواء لخفض الكوليسترول والوقاية من أمراض القلب',
    'cetirizine':    'مضاد للهستامين لعلاج الحساسية والحكة والطفح الجلدي',
}

def get_arabic_medicine_info(medicine_name):
    key = medicine_name.lower().strip()
    for kb_key, description in MEDICINE_KB.items():
        if kb_key in key or key in kb_key:
            return description
    return 'دواء موصوف من قِبَل طبيبك — يُرجى استشارة طبيبك أو الصيدلاني لمزيد من التفاصيل'

def arabic_frequency(freq):
    freq = int(freq)
    mapping = {1: 'مرة واحدة يومياً', 2: 'مرتين يومياً (كل 12 ساعة)',
               3: 'ثلاث مرات يومياً (كل 8 ساعات)', 4: 'أربع مرات يومياً (كل 6 ساعات)'}
    return mapping.get(freq, f'{freq} مرات يومياً')

def arabic_duration(days):
    days = int(days)
    if days == 1: return 'ليوم واحد فقط'
    if days == 7: return 'لمدة أسبوع'
    if days == 14: return 'لمدة أسبوعين'
    if days == 30: return 'لمدة شهر'
    return f'لمدة {days} يوماً'

@app.route('/explain-prescription', methods=['POST'])
def explain_prescription():
    try:
        data         = request.json or {}
        prescription = data.get('prescription', {})
        notes        = prescription.get('notes', '')
        items        = prescription.get('items', [])

        if not items:
            return jsonify({
                'summary': 'لا تحتوي هذه الوصفة على أي أدوية.',
                'items': []
            })

        explained_items = []
        medicine_names  = []

        for item in items:
            name         = item.get('medicine_name', 'دواء غير معروف')
            dosage       = item.get('dosage', '')
            frequency    = item.get('frequency', 1)
            duration     = item.get('duration', 1)
            instructions = item.get('instructions', '')

            purpose = get_arabic_medicine_info(name)
            freq_text = arabic_frequency(frequency)
            dur_text  = arabic_duration(duration)

            lines = [
                f'💊 {name} — {purpose}',
                f'📌 الجرعة: {dosage}',
                f'🕐 {freq_text}',
                f'📅 {dur_text}',
            ]
            if instructions:
                lines.append(f'📝 تعليمات: {instructions}')

            medicine_names.append(name)
            explained_items.append({
                'medicine':     name,
                'purpose':      purpose,
                'dosage':       dosage,
                'frequency':    frequency,
                'duration':     duration,
                'instructions': instructions,
                'explanation':  '\n'.join(lines),
                'warnings':     [],  # Warnings merged by Laravel
                'is_unknown':   purpose.startswith('دواء موصوف'),
                'category':     '',
            })

        # Build summary
        count = len(explained_items)
        med_list = '، '.join(medicine_names)
        summary = f'تحتوي وصفتك على {count} {"دواء" if count == 1 else "أدوية"}: {med_list}.\n'
        if notes:
            summary += f'\nملاحظات الطبيب: {notes}\n'
        summary += '\nيُرجى الالتزام بالجرعات والمواعيد المحددة، وإخبار طبيبك إذا ظهرت أي أعراض جانبية.'

        return jsonify({'summary': summary, 'items': explained_items})

    except Exception as e:
        return jsonify({'error': str(e)}), 400


@app.route('/health', methods=['GET'])
def health():
    return jsonify({'status': 'ok', 'service': 'Hospital AI Engine'})


if __name__ == '__main__':
    app.run(port=5005)

