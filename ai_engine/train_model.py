import pandas as pd
from sklearn.tree import DecisionTreeClassifier
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
# pyrefly: ignore [missing-import]
import joblib
import os

def train_model():
    # Load dataset
    data = pd.read_csv('dataset.csv')
    
    # Features and target
    # New features: fever, cough, headache, fatigue, chest_pain, 
    # shortness_of_breath, dizziness, nausea, sore_throat
    X = data.drop('disease', axis=1)
    y = data['disease']
    
    # Initialize and train Decision Tree
    model = DecisionTreeClassifier(random_state=42)
    model.fit(X, y)
    
    # Save the model
    joblib.dump(model, 'symptom_model.joblib')
    print(f"Model trained with {len(X.columns)} features and saved as symptom_model.joblib")
    print(f"Features: {list(X.columns)}")

def train_medication_xai_model():
    if not os.path.exists('medications_dataset.csv'):
        print("medications_dataset.csv not found")
        return
        
    data = pd.read_csv('medications_dataset.csv')
    
    features = [
        'clinical_efficacy_score', 'patient_adherence_rate', 
        'risk_level_score', 'synergy_potential', 'target_disease_severity_handled'
    ]
    
    if not all(col in data.columns for col in features):
        print("Required ML features missing from dataset!")
        return

    X = data[features]
    
    # Train Random Forest Regressor to have strong continuous splits
    y = data['treatment_success_probability']
    from sklearn.ensemble import RandomForestRegressor
    model = RandomForestRegressor(n_estimators=100, random_state=42, max_depth=5)
    model.fit(X, y)
    
    joblib.dump(model, 'medication_xai_model.joblib')
    
    print(f"Medication XAI Model trained with {len(X.columns)} features and saved as medication_xai_model.joblib")
    print(f"Medication XAI Features: {list(X.columns)}")

if __name__ == "__main__":
    # Ensure we are in the right directory
    os.chdir(os.path.dirname(os.path.abspath(__file__)))
    train_model()
    train_medication_xai_model()
