import pandas as pd
from sklearn.tree import DecisionTreeClassifier
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

if __name__ == "__main__":
    # Ensure we are in the right directory
    os.chdir(os.path.dirname(os.path.abspath(__file__)))
    train_model()
