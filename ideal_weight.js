// Get references to HTML elements
const heightInput = document.getElementById('height');
const ageInput = document.getElementById('age');
const genderSelect = document.getElementById('gender');
const calculateBtn = document.getElementById('calculateBtn');
const idealWeightDisplay = document.getElementById('idealWeight');
const healthyWeightRangeDisplay = document.getElementById('healthyWeightRange');
const weightStatusDisplay = document.getElementById('weightStatus');
const tipsDisplay = document.getElementById('tips');

// Add event listener to the calculate button
calculateBtn.addEventListener('click', calculateIdealWeight);

// Function to calculate ideal weight
function calculateIdealWeight() {
    // Read values from input fields
    const heightCm = parseFloat(heightInput.value);
    const age = parseInt(ageInput.value);
    const gender = genderSelect.value;

    // Validate height and age
    if (isNaN(heightCm) || heightCm <= 0 || isNaN(age) || age <= 0) {
        tipsDisplay.textContent = 'Error: Please enter positive numbers for height and age.';
        idealWeightDisplay.textContent = '';
        healthyWeightRangeDisplay.textContent = '';
        weightStatusDisplay.textContent = '';
        return;
    }

    // Convert height from cm to inches
    const heightInches = heightCm / 2.54;
    const heightFeet = Math.floor(heightInches / 12);
    const remainingInches = heightInches % 12;

    let idealWeightKg;

    // Calculate ideal weight using the Devine formula
    if (heightFeet >= 5) {
        const inchesOver5Feet = (heightFeet - 5) * 12 + remainingInches;
        if (gender === 'male') {
            idealWeightKg = 50 + (2.3 * inchesOver5Feet);
        } else { // female
            idealWeightKg = 45.5 + (2.3 * inchesOver5Feet);
        }
    } else {
        // For heights below 5 feet, the Devine formula might not be directly applicable.
        // We can provide a proportional estimate or a specific message.
        // For simplicity, let's assume a baseline for heights below 5 feet.
        // This is a simplification and might need adjustment based on specific requirements.
        if (gender === 'male') {
            idealWeightKg = 50 - (2.3 * ( (5*12) - heightInches) ); // Simplified reduction
        } else { // female
            idealWeightKg = 45.5 - (2.3 * ( (5*12) - heightInches) ); // Simplified reduction
        }
        // Ensure weight doesn't go unrealistically low
        if (idealWeightKg < 30) idealWeightKg = 30;
    }


    // Calculate healthy weight range (ideal weight +/- 5kg)
    const lowerBound = idealWeightKg - 5;
    const upperBound = idealWeightKg + 5;

    // Display results
    idealWeightDisplay.textContent = `${idealWeightKg.toFixed(1)} kg`;
    healthyWeightRangeDisplay.textContent = `${lowerBound.toFixed(1)} kg - ${upperBound.toFixed(1)} kg`;
    weightStatusDisplay.textContent = 'Weight status will be determined based on your current weight.';
    tipsDisplay.textContent = 'Consult a healthcare professional for personalized advice. This calculator provides an estimate.';
}
