import pandas as pd
# pyrefly: ignore [missing-import]
import numpy as np
import random
import os

def generate_med_dataset():
    np.random.seed(42)
    random.seed(42)
    
    num_samples = 2000
    
    # Features
    ages = np.random.randint(18, 90, num_samples)
    genders = np.random.choice([0, 1], num_samples) # 0: Female, 1: Male
    symptom_severity = np.random.randint(1, 11, num_samples)
    comorbidity_diabetes = np.random.choice([0, 1], num_samples, p=[0.8, 0.2])
    comorbidity_hypertension = np.random.choice([0, 1], num_samples, p=[0.7, 0.3])
    
    # Medications (simulate some common ones)
    medications = ['Lisinopril', 'Amlodipine', 'Metformin', 'Atorvastatin', 'Amoxicillin', 'Azithromycin', 'Omeprazole', 'Ibuprofen', 'Acetaminophen', 'Albuterol', 'Losartan', 'Levothyroxine', 'Sertraline']
    medication_choices = np.random.choice(medications, num_samples)
    
    treatment_success = []
    
    for i in range(num_samples):
        med = medication_choices[i]
        severity = symptom_severity[i]
        age = ages[i]
        diab = comorbidity_diabetes[i]
        hyp = comorbidity_hypertension[i]
        
        # Calculate a mock success probability based on features
        prob = 0.6
        
        # Rules to make features important
        if med in ['Lisinopril', 'Amlodipine', 'Losartan'] and hyp == 1:
            prob += 0.3
        if med == 'Metformin' and diab == 1:
            prob += 0.35
            
        if severity > 8:
            prob -= 0.2
        if age > 75:
            prob -= 0.15
            
        prob = max(0.1, min(0.95, prob))
        success = str(np.random.choice([1, 0], p=[prob, 1-prob])) # ensure string if needed, let's keep integer
        treatment_success.append(int(success))
        
    df = pd.DataFrame({
        'age': ages,
        'gender': genders,
        'symptom_severity': symptom_severity,
        'comorbidity_diabetes': comorbidity_diabetes,
        'comorbidity_hypertension': comorbidity_hypertension,
        'medication': medication_choices,
        'treatment_success': treatment_success
    })
    
    df.to_csv('medication_prescriptions.csv', index=False)
    print("Dataset generated: medication_prescriptions.csv")

if __name__ == '__main__':
    os.chdir(os.path.dirname(os.path.abspath(__file__)))
    generate_med_dataset()
