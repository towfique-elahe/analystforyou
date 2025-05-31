<?php

// find match section [find_match]
function render_find_match() {
    ob_start();
?>

<div id="findMatch">
    <!-- categories -->
    <div class="container categories-container">
        <h3 class="heading">Select a Category</h3>
        <p class="sub-heading">Choose the category that best matches your needs</p>
        <div class="options categories">
            <a href="javascript:void()" class="option category"><ion-icon name="code-outline"></ion-icon>Software
                Development</a>
            <a href="javascript:void()" class="option category"><ion-icon name="server-outline"></ion-icon>Data & AI</a>
            <a href="javascript:void()" class="option category"><ion-icon name="options-outline"></ion-icon>IT
                Operations & Infrastructure</a>
            <a href="javascript:void()" class="option category"><ion-icon
                    name="shield-outline"></ion-icon>Cybersecurity</a>
            <a href="javascript:void()" class="option category"><ion-icon name="briefcase-outline"></ion-icon>Project &
                Business</a>
            <a href="javascript:void()" class="option category"><ion-icon
                    name="checkmark-circle-outline"></ion-icon>Testing & Quality Assurance</a>
            <a href="javascript:void()" class="option category"><ion-icon name="globe-outline"></ion-icon>Web &
                Design</a>
        </div>
    </div>

    <!-- roles -->
    <div class="container roles-container">
        <h3 class="heading">Select a Role</h3>
        <p class="sub-heading">Choose the role you're looking for</p>
        <div class="options roles">
            <a href="javascript:void()" class="option role"><ion-icon name="code-working-outline"></ion-icon>Fullstack
                Developer</a>
            <a href="javascript:void()" class="option role"><ion-icon name="server-outline"></ion-icon>Backend
                Developer</a>
            <a href="javascript:void()" class="option role"><ion-icon name="color-palette-outline"></ion-icon>Frontend
                Developer</a>
            <a href="javascript:void()" class="option role"><ion-icon name="apps-outline"></ion-icon>Mobile Developer
                (Flutter, React Native, iOS, Android)</a>
            <a href="javascript:void()" class="option role"><ion-icon name="settings-outline"></ion-icon>DevOps
                Engineer</a>
            <a href="javascript:void()" class="option role"><ion-icon name="layers-outline"></ion-icon>Software
                Architect / Tech Lead</a>
            <a href="javascript:void()" class="option role"><ion-icon name="hardware-chip-outline"></ion-icon>Embedded
                Software Engineer</a>
        </div>
        <button class="back-button">Back</button>
    </div>

    <!-- experiences -->
    <div class="container experiences-container">
        <h3 class="heading">Select Experience Level</h3>
        <p class="sub-heading">Choose the experience level you're looking for</p>
        <div class="options experiences">
            <a href="javascript:void()" class="option experience"><ion-icon name="sparkles-outline"></ion-icon>Junior
                (0-2 years)</a>
            <a href="javascript:void()" class="option experience"><ion-icon name="trending-up-outline"></ion-icon>Medior
                (2-5 years)</a>
            <a href="javascript:void()" class="option experience"><ion-icon name="trophy-outline"></ion-icon>Senior (5+
                years)</a>
        </div>
        <button class="back-button">Back</button>
    </div>

    <!-- availabilities -->
    <div class="container availabilities-container">
        <h3 class="heading">Choose Your Availability</h3>
        <p class="sub-heading">Select the availability type you're looking for</p>
        <div class="options availabilities">
            <a href="javascript:void()" class="option availability"><ion-icon
                    name="briefcase-outline"></ion-icon>Freelancer</a>
            <a href="javascript:void()" class="option availability"><ion-icon
                    name="time-outline"></ion-icon>Part-time</a>
            <a href="javascript:void()" class="option availability"><ion-icon
                    name="hourglass-outline"></ion-icon>Full-time</a>
            <a href="javascript:void()" class="option availability"><ion-icon
                    name="swap-horizontal-outline"></ion-icon>Open to both freelance and employment</a>
        </div>
        <button class="back-button">Back</button>
    </div>

    <!-- industries -->
    <div class="container industries-container">
        <h3 class="heading">Select Industry</h3>
        <p class="sub-heading">Choose the industry you're targeting</p>
        <div class="options industries">
            <a href="javascript:void()" class="option industry"><ion-icon name="card-outline"></ion-icon>Financial
                Services</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="medkit-outline"></ion-icon>Healthcare &
                Medical</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="cart-outline"></ion-icon>E-Commerce &
                Retail</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="cube-outline"></ion-icon>Logistics &
                Supply Chain</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="business-outline"></ion-icon>Government
                & Public Sector</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="wifi-outline"></ion-icon>Telecom & IT
                Services</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="school-outline"></ion-icon>Education &
                Research</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="flash-outline"></ion-icon>Energy &
                Utilities</a>
            <a href="javascript:void()" class="option industry"><ion-icon
                    name="construct-outline"></ion-icon>Manufacturing & Industry</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="home-outline"></ion-icon>Real Estate &
                Construction</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="megaphone-outline"></ion-icon>Media &
                Marketing</a>
            <a href="javascript:void()" class="option industry"><ion-icon name="heart-outline"></ion-icon>Non-profit &
                NGOs</a>
        </div>
        <button class="back-button">Back</button>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const steps = [
            ".categories-container",
            ".roles-container",
            ".experiences-container",
            ".availabilities-container",
            ".industries-container"
        ];

        let currentStep = 0;
        const selections = {};

        function showStep(index) {
            steps.forEach((step, i) => {
                const el = document.querySelector(step);
                if (el) {
                    el.classList.toggle('active', i === index);
                    if (i === index) {
                        const stepKey = step.replace('.', '').replace('-container', '');
                        const selectedValue = selections[stepKey];

                        if (selectedValue) {
                            // Highlight the previously selected option
                            el.querySelectorAll(".option").forEach(option => {
                                const text = option.textContent.trim();
                                if (text === selectedValue) {
                                    option.classList.add("selected");
                                } else {
                                    option.classList.remove("selected");
                                }
                            });
                        }
                    }
                }
            });
        }

        showStep(currentStep); // Initial render

        steps.forEach((step, index) => {
            const container = document.querySelector(step);
            if (!container) return;

            container.querySelectorAll(".option").forEach(option => {
                option.addEventListener("click", function () {
                    const value = option.textContent.trim();
                    const stepKey = step.replace('.', '').replace('-container', '');

                    selections[stepKey] = value;
                    console.log(selections);

                    option.classList.add("selected");

                    if (index + 1 < steps.length) {
                        currentStep = index + 1;
                        showStep(currentStep);
                    } else {
                        alert("All steps completed!");
                        // AJAX submission can happen here
                        // Example: sendSelectionsToServer(selections);
                    }
                });
            });

            const backButton = container.querySelector(".back-button");
            if (backButton) {
                backButton.addEventListener("click", function () {
                    if (index > 0) {
                        currentStep = index - 1;
                        showStep(currentStep);
                    }
                });
            }
        });
    });
</script>

<?php
    return ob_get_clean();
}

add_shortcode('find_match', 'render_find_match');