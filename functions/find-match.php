<?php
function render_find_match() {
    ob_start();
?>
<div id="findMatch">
    <!-- Specialization -->
    <div class="container specialization-container">
        <h3 class="heading">Select a Specialization</h3>
        <p class="sub-heading">Choose the general role you're looking for</p>
        <div class="options specialization">
            <a href="javascript:void()" class="option specialization">Data Analyst</a>
            <a href="javascript:void()" class="option specialization">Business Analyst</a>
            <a href="javascript:void()" class="option specialization">BI Specialist</a>
            <a href="javascript:void()" class="option specialization">Data Scientist</a>
            <a href="javascript:void()" class="option specialization">Data Engineer</a>
            <a href="javascript:void()" class="option specialization">Information Analyst</a>
        </div>
    </div>

    <!-- Sub Role -->
    <div class="container subrole-container">
        <h3 class="heading">Select a Sub Role</h3>
        <p class="sub-heading">Refine the role with a more specific specialization</p>
        <div class="options subrole"></div>
        <button class="button back-button">Back</button>
    </div>

    <!-- Languages & Tools -->
    <div class="container tools-container">
        <h3 class="heading">Select Languages & Tools</h3>
        <p class="sub-heading">Select one or more technical skills or tools</p>
        <div class="options tools multi-select">
            <?php
            $tools = ["SQL", "Python", "R", "Power BI", "Tableau", "Excel", "Google Data Studio", "scikit-learn", "TensorFlow", "Apache Airflow", "Snowflake", "Azure / AWS / GCP", "Git"];
            foreach ($tools as $tool) {
                echo "<a href='javascript:void()' class='option tool'>$tool</a>";
            }
            ?>
        </div>
        <button class="button back-button">Back</button>
    </div>

    <!-- Experience -->
    <div class="container experience-container">
        <h3 class="heading">Select Experience Level</h3>
        <p class="sub-heading">Choose the desired experience range</p>
        <div class="options experience">
            <a href="javascript:void()" class="option experience">Junior (0–2 years)</a>
            <a href="javascript:void()" class="option experience">Mid-level (3–5 years)</a>
            <a href="javascript:void()" class="option experience">Senior (6+ years)</a>
        </div>
        <button class="button back-button">Back</button>
    </div>

    <!-- Availability -->
    <div class="container availability-container">
        <h3 class="heading">Select Availability</h3>
        <p class="sub-heading">Select the type of availability the candidate is looking for</p>
        <div class="options availability">
            <a href="javascript:void()" class="option availability">Full-time</a>
            <a href="javascript:void()" class="option availability">Part-time</a>
            <a href="javascript:void()" class="option availability">Freelancer</a>
            <a href="javascript:void()" class="option availability">Traineeship</a>
            <a href="javascript:void()" class="option availability">Internship</a>
        </div>
        <button class="button back-button">Back</button>
    </div>

    <!-- Sector -->
    <div class="container sector-container">
        <h3 class="heading">Select Sector</h3>
        <p class="sub-heading">Select industry experience or preference</p>
        <div class="options sector">
            <a href="javascript:void()" class="option sector">Finance</a>
            <a href="javascript:void()" class="option sector">Healthcare</a>
            <a href="javascript:void()" class="option sector">Government</a>
            <a href="javascript:void()" class="option sector">Retail & E-commerce</a>
            <a href="javascript:void()" class="option sector">Logistics</a>
            <a href="javascript:void()" class="option sector">Technology & IT</a>
            <a href="javascript:void()" class="option sector">Energy</a>
            <a href="javascript:void()" class="option sector">Education</a>
            <a href="javascript:void()" class="option sector">Consulting</a>
            <a href="javascript:void()" class="option sector">Other / Not Relevant</a>
        </div>
        <button class="button back-button">Back</button>
    </div>

    <!-- Recruiter -->
    <div class="container recruiter-container">
        <h3 class="heading">Your Details</h3>
        <p class="sub-heading">Please provide your contact information</p>
        <form class="form recruiter-form">
            <div class="form-group">
                <label for="recruiter-name">Your Name</label>
                <input type="text" id="recruiter-name" name="recruiter-name" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="recruiter-email">Your Email</label>
                <input type="email" id="recruiter-email" name="recruiter-email" placeholder="Enter your email address">
            </div>
            <div class="form-group">
                <label for="recruiter-phone">Your Phone</label>
                <input type="tel" id="recruiter-phone" name="recruiter-phone" placeholder="Enter your phone number">
            </div>
            <div class="button-group">
                <button type="button" class="button back-button">Back</button>
                <button type="button" class="button find-button">Find My Match</button>
            </div>
        </form>
    </div>

    <!-- Loading -->
    <div class="container loading-container">
        <h3 class="heading">Finding your match...</h3>
    </div>

    <!-- Matches -->
    <div class="container match-container">
        <h3 class="heading">3 Matches Found</h3>
        <div class="match-cards">
            <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="col card">
                <div class="row head">
                    <div class="col">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/media/user.png" alt="User"
                            class="image">
                    </div>
                    <div class="col">
                        <h4 class="name">Candidate
                            <?= $i ?>
                        </h4>
                        <p class="specialization">BI Specialist</p>
                        <div class="flags">
                            🇺🇸 🇳🇱
                        </div>
                    </div>
                </div>
                <div class="body">
                    <div class="tools">
                        <span class="tool">Power BI</span>
                        <span class="tool">SQL</span>
                        <span class="tool">Excel</span>
                    </div>
                    <p class="bio">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Veniam, quibusdam!</p>
                    <p><strong>Sub Role:</strong> Power BI Specialist</p>
                    <p><strong>Experience:</strong> Mid-level (3–5 years)</p>
                    <p><strong>Availability:</strong> Full-time</p>
                    <p><strong>Sector:</strong> Finance</p>
                </div>
                <div class="footer">
                    <button class="button">Contact Now</button>
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const steps = [
        ".specialization-container",
        ".subrole-container",
        ".tools-container",
        ".experience-container",
        ".availability-container",
        ".sector-container",
        ".recruiter-container",
        ".loading-container",
        ".match-container"
    ];

    const subrolesMap = {
        "Data Analyst": ["Reporting Analyst", "Marketing Data Analyst", "Customer Insights Analyst",
            "Financial Data Analyst", "Operations Analyst", "Supply Chain Analyst",
            "Statistical Analyst"
        ],
        "Business Analyst": ["Process Analyst", "Change Analyst", "Functional Business Analyst",
            "Business Process Analyst"
        ],
        "BI Specialist": ["BI Analyst", "BI Developer", "Power BI Specialist", "Tableau Specialist",
            "Dashboard Specialist", "Data Visualization Specialist"
        ],
        "Data Scientist": ["Predictive Analytics Specialist", "Machine Learning Engineer", "AI Engineer",
            "NLP Specialist", "Quantitative Analyst"
        ],
        "Data Engineer": ["ETL Developer", "Analytics Engineer", "Data Platform Engineer",
            "Data Integration Specialist", "Data Architect", "Data Quality Analyst", "Data Steward"
        ],
        "Information Analyst": ["Functional Analyst", "Technical Analyst", "Systems Analyst",
            "Application Analyst", "Requirements Analyst"
        ]
    };

    let currentStep = 0;
    const selections = {
        tools: []
    };

    function showStep(index) {
        steps.forEach((step, i) => {
            const el = document.querySelector(step);
            if (el) el.classList.toggle('active', i === index);
        });

        const stepKey = steps[index].replace('.', '').replace('-container', '');
        highlightSelectedOptions(stepKey);

        if (steps[index] === ".loading-container") {
            setTimeout(() => {
                currentStep++;
                showStep(currentStep);
            }, 2000);
        }
    }

    function highlightSelectedOptions(key) {
        const container = document.querySelector(`.${key}-container`);
        if (!container) return;

        container.querySelectorAll(".option").forEach(option => {
            const val = option.textContent.trim();
            if (key === "tools") {
                option.classList.toggle("selected", selections.tools.includes(val));
            } else {
                option.classList.toggle("selected", selections[key] === val);
            }
        });
    }

    function goToNextStep() {
        currentStep++;
        showStep(currentStep);
    }

    showStep(currentStep);

    document.querySelectorAll(".specialization-container .option").forEach(option => {
        option.addEventListener("click", function() {
            selections.specialization = this.textContent.trim();

            const subroleContainer = document.querySelector(".subrole");
            subroleContainer.innerHTML = subrolesMap[selections.specialization]
                .map(role => `<a href="javascript:void()" class="option subrole">${role}</a>`)
                .join("");

            goToNextStep();

            setTimeout(() => {
                document.querySelectorAll(".subrole-container .option").forEach(
                    subrole => {
                        subrole.addEventListener("click", function() {
                            selections.subrole = this.textContent.trim();
                            goToNextStep();
                        });
                    });
            }, 100);
        });
    });

    // Handle Tools multi-select
    document.querySelectorAll(".tools-container .option").forEach(option => {
        option.addEventListener("click", function() {
            const val = this.textContent.trim();
            if (selections.tools.includes(val)) {
                selections.tools = selections.tools.filter(t => t !== val);
                this.classList.remove("selected");
            } else {
                selections.tools.push(val);
                this.classList.add("selected");
            }
        });
    });

    // Add a "Next" button for tools step
    const nextButton = document.createElement("button");
    nextButton.textContent = "Next";
    nextButton.className = "button next-button";
    nextButton.addEventListener("click", function() {
        goToNextStep();
    });
    document.querySelector(".tools-container").appendChild(nextButton);

    // Handle generic steps
    steps.forEach((step, index) => {
        const container = document.querySelector(step);
        if (!container) return;

        const options = container.querySelectorAll(
            ".option:not(.tool):not(.subrole):not(.specialization)");
        options.forEach(option => {
            option.addEventListener("click", function() {
                const val = this.textContent.trim();
                const key = step.replace('.', '').replace('-container', '');
                selections[key] = val;
                goToNextStep();
            });
        });

        const backButton = container.querySelector(".back-button");
        if (backButton) {
            backButton.addEventListener("click", function() {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });
        }
    });

    // Handle "Find My Match" click
    const findButton = document.querySelector(".recruiter-container .find-button");
    if (findButton) {
        findButton.addEventListener("click", function() {
            // Optionally, collect recruiter form values here
            selections.recruiter = {
                name: document.getElementById("recruiter-name").value.trim(),
                email: document.getElementById("recruiter-email").value.trim(),
                phone: document.getElementById("recruiter-phone").value.trim(),
            };

            // Proceed to loading
            currentStep++;
            showStep(currentStep);
        });
    }

});
</script>

<?php
    return ob_get_clean();
}

add_shortcode('find_match', 'render_find_match');