import pandas as pd
from sklearn.tree import DecisionTreeClassifier
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import LabelEncoder
# pyrefly: ignore [missing-import]
import joblib
import os

from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error, r2_score
from sklearn.tree import DecisionTreeClassifier
from sklearn.metrics import accuracy_score, classification_report


def train_model():

    # 📥 Load dataset
    data = pd.read_csv('dataset.csv')

    # 🎯 Features & Target
    X = data.drop('disease', axis=1)
    y = data['disease']

    # 🔪 Split data (70 / 15 / 15)
    X_train, X_temp, y_train, y_temp = train_test_split(
        X, y, test_size=0.3, random_state=42, stratify=y
    )

    X_val, X_test, y_val, y_test = train_test_split(
        X_temp, y_temp, test_size=0.5, random_state=42, stratify=y_temp
    )

    print(f"Train: {len(X_train)}, Val: {len(X_val)}, Test: {len(X_test)}")

    # 🌳 Model
    model = DecisionTreeClassifier(
        random_state=42,
        max_depth=5  # علشان تقلل overfitting
    )

    # 🟢 Train
    model.fit(X_train, y_train)

    # 🟡 Validation
    y_val_pred = model.predict(X_val)

    print("\n📊 Validation Accuracy:", accuracy_score(y_val, y_val_pred))
    print(classification_report(y_val, y_val_pred))

    # 🔵 Test (Final)
    y_test_pred = model.predict(X_test)

    print("\n🧪 Test Accuracy:", accuracy_score(y_test, y_test_pred))
    print(classification_report(y_test, y_test_pred))

    # 💾 Save
    joblib.dump(model, 'symptom_model.joblib')

    print("\n✅ Model saved as symptom_model.joblib")
    print(f"Features: {list(X.columns)}")

def train_medication_xai_model():

    if not os.path.exists('medications_dataset.csv'):
        print("medications_dataset.csv not found")
        return

    data = pd.read_csv('medications_dataset.csv')

    features = [
        'clinical_efficacy_score',
        'patient_adherence_rate',
        'risk_level_score',
        'synergy_potential',
        'target_disease_severity_handled'
    ]

    if not all(col in data.columns for col in features):
        print("Required ML features missing from dataset!")
        return

    X = data[features]
    y = data['treatment_success_probability']

    # 🔥 تقسيم الداتا
    X_train, X_temp, y_train, y_temp = train_test_split(
        X, y, test_size=0.3, random_state=42
    )

    X_val, X_test, y_val, y_test = train_test_split(
        X_temp, y_temp, test_size=0.5, random_state=42
    )

    print(f"Train size: {len(X_train)}")
    print(f"Validation size: {len(X_val)}")
    print(f"Test size: {len(X_test)}")

    # 🧠 إنشاء الموديل
    model = RandomForestRegressor(
        n_estimators=100,
        random_state=42,
        max_depth=5
    )

    # 🟢 تدريب
    model.fit(X_train, y_train)

    # 🟡 تقييم على validation
    y_val_pred = model.predict(X_val)

    print("\n📊 Validation Results:")
    print("MAE:", mean_absolute_error(y_val, y_val_pred))
    print("R2:", r2_score(y_val, y_val_pred))

    # 🔵 تقييم نهائي على test
    y_test_pred = model.predict(X_test)

    print("\n🧪 Test Results:")
    print("MAE:", mean_absolute_error(y_test, y_test_pred))
    print("R2:", r2_score(y_test, y_test_pred))

    # 💾 حفظ الموديل
    joblib.dump(model, 'medication_xai_model.joblib')

    print("\n✅ Model saved as medication_xai_model.joblib")
    print(f"Features used: {list(X.columns)}")

if __name__ == "__main__":
    # Ensure we are in the right directory
    os.chdir(os.path.dirname(os.path.abspath(__file__)))
    train_model()
    train_medication_xai_model()
