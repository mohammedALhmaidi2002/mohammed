// Get references to HTML elements
const weightInput = document.getElementById('weight');
const heightInput = document.getElementById('height');
const ageInput = document.getElementById('age');
const genderSelect = document.getElementById('gender');
const activityLevelSelect = document.getElementById('activityLevel');
const calculateBtn = document.getElementById('calculateBtn');
const maintenanceCaloriesDisplay = document.getElementById('maintenanceCalories');
const lossCaloriesDisplay = document.getElementById('lossCalories');
const gainCaloriesDisplay = document.getElementById('gainCalories');
const tipsDisplay = document.getElementById('tips');

// Add event listener to the calculate button
calculateBtn.addEventListener('click', calculateCalories);

// Function to calculate calories
function calculateCalories() {
    // Read values from input fields
    const weightKg = parseFloat(weightInput.value);
    const heightCm = parseFloat(heightInput.value);
    const age = parseInt(ageInput.value);
    const gender = genderSelect.value;
    const activityLevel = activityLevelSelect.value;

    // Validate inputs
    if (isNaN(weightKg) || weightKg <= 0 || isNaN(heightCm) || heightCm <= 0 || isNaN(age) || age <= 0) {
        tipsDisplay.textContent = 'Error: Please enter positive numbers for weight, height, and age.';
        maintenanceCaloriesDisplay.textContent = '';
        lossCaloriesDisplay.textContent = '';
        gainCaloriesDisplay.textContent = '';
        return;
    }

    // Calculate BMR using Mifflin-St Jeor equation
    let bmr;
    if (gender === 'male') {
        bmr = (10 * weightKg) + (6.25 * heightCm) - (5 * age) + 5;
    } else { // female
        bmr = (10 * weightKg) + (6.25 * heightCm) - (5 * age) - 161;
    }

    // Determine activity factor
    let activityFactor;
    switch (activityLevel) {
        case 'light':
            activityFactor = 1.375;
            break;
        case 'moderate':
            activityFactor = 1.55;
            break;
        case 'active':
            activityFactor = 1.725;
            break;
        case 'very_active':
            activityFactor = 1.9;
            break;
        default:
            activityFactor = 1.375; // Default to light
    }

    // Calculate TDEE (maintenance calories)
    const tdee = bmr * activityFactor;

    // Calculate calories for weight loss and gain
    const lossCalories = tdee - 500;
    const gainCalories = tdee + 500;

    // Display results
    maintenanceCaloriesDisplay.textContent = `${tdee.toFixed(0)} kcal`;
    lossCaloriesDisplay.textContent = `${lossCalories.toFixed(0)} kcal`;
    gainCaloriesDisplay.textContent = `${gainCalories.toFixed(0)} kcal`;
    tipsDisplay.textContent = 'These are estimates. Consult a nutritionist for personalized advice. Ensure a safe calorie deficit/surplus.';
}
