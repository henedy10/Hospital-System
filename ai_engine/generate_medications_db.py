import pandas as pd
# pyrefly: ignore [missing-import]
import numpy as np
import random
import os

def generate_db():
    np.random.seed(42)
    random.seed(42)

    medicines = [
        ("Lisinopril", "ACE Inhibitor", "Hypertension", "10-40 mg"),
        ("Amlodipine", "Calcium Channel Blocker", "Hypertension", "5-10 mg"),
        ("Metformin", "Biguanide", "Type 2 Diabetes", "500-2000 mg"),
        ("Atorvastatin", "Statin", "High Cholesterol", "10-80 mg"),
        ("Levothyroxine", "Thyroid Hormone", "Hypothyroidism", "25-200 mcg"),
        ("Amoxicillin", "Antibiotic", "Bacterial Infection", "250-500 mg"),
        ("Azithromycin", "Macrolide Antibiotic", "Bacterial Infection", "250-500 mg"),
        ("Omeprazole", "Proton Pump Inhibitor", "Acid Reflux", "20-40 mg"),
        ("Losartan", "ARB", "Hypertension", "25-100 mg"),
        ("Albuterol", "Bronchodilator", "Asthma", "1-2 puffs"),
        ("Gabapentin", "Anticonvulsant", "Nerve Pain", "300-1200 mg"),
        ("Sertraline", "SSRI", "Depression", "50-200 mg"),
        ("Simvastatin", "Statin", "High Cholesterol", "10-40 mg"),
        ("Montelukast", "Leukotriene Receptor Antagonist", "Asthma", "10 mg"),
        ("Pantoprazole", "Proton Pump Inhibitor", "Acid Reflux", "20-40 mg"),
        ("Escitalopram", "SSRI", "Anxiety", "10-20 mg"),
        ("Fluoxetine", "SSRI", "Depression", "20-60 mg"),
        ("Rosuvastatin", "Statin", "High Cholesterol", "5-40 mg"),
        ("Bupropion", "NDRI", "Depression", "150-300 mg"),
        ("Doxycycline", "Tetracycline Antibiotic", "Bacterial Infection", "100-200 mg"),
        ("Meloxicam", "NSAID", "Arthritis", "7.5-15 mg"),
        ("Clopidogrel", "Antiplatelet", "Blood Clots", "75 mg"),
        ("Propranolol", "Beta-blocker", "Anxiety/Tremors", "10-40 mg"),
        ("Aspirin", "NSAID", "Pain", "81-325 mg"),
        ("Citalopram", "SSRI", "Depression", "20-40 mg"),
        ("Trazodone", "SARI", "Insomnia", "50-100 mg"),
        ("Carvedilol", "Beta-blocker", "Heart Failure", "3.125-25 mg"),
        ("Metoprolol", "Beta-blocker", "Hypertension", "25-100 mg"),
        ("Duloxetine", "SNRI", "Nerve Pain", "30-60 mg"),
        ("Venlafaxine", "SNRI", "Anxiety", "75-225 mg")
    ]

    data = []
    
    for name, m_class, target_disease, dosage in medicines:
        # Base string properties
        usage_en = target_disease
        onset_time = random.choice(["30 mins", "1 hour", "2 hours", "1-2 weeks", "24 hours"])
        duration = random.choice(["12 hours", "24 hours", "6 hours", "48 hours"])
        side_effects_en = random.choice(["Dizziness; Nausea", "Fatigue; Headache", "Constipation", "Dry Mouth", "Stomach upset", "Insomnia"])
        how_it_works_en = "Mechanism of action regulates bodily pathways specific to " + m_class.lower() + "."
        why_prescribed_en = "Prescribed to treat and manage symptoms associated with " + target_disease.lower() + "."
        treatment_goal_en = "Stabilize patient condition and mitigate long-term impacts of " + target_disease.lower() + "."
        lifestyle_en = "Maintain healthy diet; Exercise regularly; Stay hydrated"
        warnings_en = "Follow clinical instructions; Consult healthcare provider before stopping"

        # New Numerical ML Features
        clinical_efficacy_score = np.random.randint(5, 10) # 5-9
        patient_adherence_rate = np.random.randint(60, 95)
        risk_level_score = np.random.randint(1, 6)
        synergy_potential = np.random.randint(3, 9)
        target_disease_severity_handled = np.random.randint(4, 10)
        
        # Determine success probability (Target)
        success_prob = 0.5 + (clinical_efficacy_score * 0.03) + (patient_adherence_rate * 0.002) - (risk_level_score * 0.04) + (synergy_potential * 0.01)
        success_prob = max(0.2, min(0.98, success_prob))
        
        data.append([
            name, m_class, usage_en, dosage, onset_time, duration, side_effects_en, 
            how_it_works_en, why_prescribed_en, treatment_goal_en, lifestyle_en, warnings_en,
            clinical_efficacy_score, patient_adherence_rate, risk_level_score, synergy_potential,
            target_disease_severity_handled, success_prob
        ])

    df = pd.DataFrame(data, columns=[
        'name', 'drug_class', 'usage_en', 'dosage_en', 'onset_time', 'duration', 'side_effects_en',
        'how_it_works_en', 'why_prescribed_en', 'treatment_goal_en', 'lifestyle_recommendations_en', 'warnings_en',
        'clinical_efficacy_score', 'patient_adherence_rate', 'risk_level_score', 'synergy_potential',
        'target_disease_severity_handled', 'treatment_success_probability'
    ])

    df.to_csv('medications_dataset.csv', index=False)
    print(f"Generated {len(df)} rows in medications_dataset.csv with ML features.")

if __name__ == '__main__':
    os.chdir(os.path.dirname(os.path.abspath(__file__)))
    generate_db()
