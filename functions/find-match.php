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
                <label for="recruiter-name">Your Name <span class="required">*</span></label>
                <input type="text" id="recruiter-name" name="recruiter-name" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="recruiter-email">Your Email <span class="required">*</span></label>
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
        <h3 class="heading match-heading">Matches Found</h3>
        <div class="match-cards"></div>
    </div>

    <!-- Contact Form Container -->
    <div class="container contact-form-container">
        <h3 class="heading">Recruiter Contact Form</h3>
        <form class="form contact-form">
            <div class="form-group">
                <label for="contact-recruiter-name">Your Name <span class="required">*</span></label>
                <input type="text" id="contact-recruiter-name" name="contact-recruiter-name"
                    placeholder="Enter your full name" required>
            </div>
            <div class="form-group">
                <label for="contact-company-name">Company Name <span class="required">*</span></label>
                <input type="text" id="contact-company-name" name="contact-company-name"
                    placeholder="Enter your company name" required>
            </div>
            <div class="form-group">
                <label for="contact-email">Your Email Address <span class="required">*</span></label>
                <input type="email" id="contact-email" name="contact-email" placeholder="e.g., you@example.com"
                    required>
            </div>
            <div class="form-group">
                <label for="contact-phone">Phone Number</label>
                <input type="tel" id="contact-phone" name="contact-phone" placeholder="e.g., +1234567890">
            </div>
            <div class="form-group">
                <label for="contact-role">Candidate Role</label>
                <input type="text" id="contact-role" name="contact-role" placeholder="Candidate Role" readonly>
            </div>
            <div class="form-group">
                <label for="contact-message">Message / Why You're Interested <span class="required">*</span></label>
                <textarea id="contact-message" name="contact-message"
                    placeholder="Briefly explain your interest in this candidate" required></textarea>
            </div>
            <div class="form-group">
                <label for="contact-urgency">How urgent is this need? <span class="required">*</span></label>
                <select id="contact-urgency" name="contact-urgency" required>
                    <option value="" disabled selected>Select urgency level</option>
                    <option>Immediate</option>
                    <option>Within 1 week</option>
                    <option>Flexible</option>
                </select>
            </div>
            <div class="form-group">
                <label>Preferred Contact Method <span class="required">*</span></label>
                <select id="contact-method" name="contact-method" required>
                    <option value="" disabled selected>Select preferred contact method</option>
                    <option>Email</option>
                    <option>Phone</option>
                    <option>Either</option>
                </select>
            </div>
            <div class="form-group consent-group">
                <input type="checkbox" id="contact-consent" required>
                <label for="contact-consent">I agree to be contacted and that this message will be sent to the platform
                    admin.</label>
            </div>
            <div class="button-group">
                <button type="button" class="button back-button">Back</button>
                <button type="submit" class="button submit-button">Send Request</button>
            </div>
        </form>
        <div class="confirmation-message" style="display:none;">
            <p>Thank you, your message has been sent to the admin. You'll be contacted shortly.</p>
        </div>
    </div>

</div>

<?php
$ajax_url = admin_url('admin-ajax.php');
$theme_url = get_template_directory_uri();
?>

<script>
// Generate consistent background color from a string
function stringToColor(str) {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    const color = `hsl(${hash % 360}, 70%, 60%)`; // nice pastel range
    return color;
}

// Obfuscate last name like "W**H"
function obfuscateLastName(name = "") {
    if (!name || name.length < 2) return "*";
    return `${name.charAt(0).toUpperCase()}**${name.charAt(name.length - 1).toUpperCase()}`;
}

document.addEventListener("DOMContentLoaded", function() {
    const ajaxUrl = "<?php echo esc_url($ajax_url); ?>";
    const fallbackImage = "<?php echo esc_url($theme_url . '/assets/media/user.png'); ?>";

    const steps = [
        ".specialization-container",
        ".subrole-container",
        ".tools-container",
        ".experience-container",
        ".availability-container",
        ".sector-container",
        ".recruiter-container",
        ".loading-container",
        ".match-container",
        ".contact-form-container"
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
            const nameInput = document.getElementById("recruiter-name");
            const emailInput = document.getElementById("recruiter-email");

            const name = nameInput.value.trim();
            const email = emailInput.value.trim();

            // Validate Name and Email
            if (!name || !email) {
                alert("Please enter your name and email to find your match.");
                if (!name) nameInput.focus();
                else emailInput.focus();
                return;
            }

            selections.recruiter = {
                name,
                email,
                phone: document.getElementById("recruiter-phone").value.trim(),
            };

            // Show loading
            currentStep++;
            showStep(currentStep);

            // Send AJAX request to find candidates
            fetch(ajaxUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: new URLSearchParams({
                        action: "find_best_candidates",
                        data: JSON.stringify(selections),
                    }),
                })
                .then((res) => res.json())
                .then((candidates) => {
                    const container = document.querySelector(".match-cards");
                    container.innerHTML = ""; // Clear previous matches

                    // update dynamic heading
                    document.querySelector(".match-heading").textContent =
                        `${candidates.length} Match${candidates.length !== 1 ? "es" : ""} Found`;

                    if (candidates.length === 0) {
                        container.innerHTML =
                            "<p class='no-match'>No matching candidates found.</p>";
                    } else {
                        candidates.forEach((candidate) => {
                            const card = `
        <div class="card">
            <div class="row head">
                <div class="col">
                    <div class="avatar-container">
                        <div class="avatar" style="background-color: ${stringToColor(candidate.first_name)};">
                            ${candidate.first_name?.charAt(0).toUpperCase() || ''}
                        </div>
                    </div>
                </div>
                <div class="col">
                    <h4 class="name">${candidate.first_name} ${obfuscateLastName(candidate.last_name)}</h4>
                    <p class="specialization">${candidate.specialization}</p>
                    <div class="flags">
                        ${candidate.country?.includes("United States") ? "🇺🇸" : ""}
                        ${candidate.country?.includes("Netherlands") ? "🇳🇱" : ""}
                    </div>
                </div>
            </div>
            <div class="body">
                <div class="tools">
                    ${candidate.lang_tools?.split(',').map(tool => `<span class="tool">${tool.trim()}</span>`).join('') || ''}
                </div>
                <p class="bio">${candidate.bio || 'No bio available.'}</p>
                <p><strong>Sub Role:</strong> ${candidate.sub_role}</p>
                <p><strong>Experience:</strong> ${candidate.experience}</p>
                <p><strong>Availability:</strong> ${candidate.availability}</p>
                <p><strong>Sector:</strong> ${candidate.sector}</p>
            </div>
            <div class="footer">
                <button class="button">Contact Now</button>
            </div>
        </div>`;
                            container.innerHTML += card;
                        });

                        // Attach event to Contact Now buttons after loading matches
                        document.querySelectorAll(".match-container .card .footer .button").forEach(
                            button => {
                                button.addEventListener("click", function() {
                                    const card = this.closest('.card');
                                    const role = card.querySelector('.specialization')
                                        .textContent.trim();

                                    document.getElementById("contact-role").value =
                                        role;

                                    currentStep++;
                                    showStep(currentStep);
                                });
                            });
                    }
                });
        });
    }

    // Form submission handling
    document.querySelector('.contact-form').addEventListener('submit', function(event) {
        event.preventDefault();

        // Optional: AJAX submission to server here

        // Show confirmation message
        document.querySelector('.contact-form').style.display = 'none';
        document.querySelector('.confirmation-message').style.display = 'block';
    });

    // Handle Back button in contact form
    document.querySelector('.contact-form-container .back-button').addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });

});
</script>

<?php
    return ob_get_clean();
}
add_shortcode('find_match', 'render_find_match');

// Finding candidates AJAX handler
add_action('wp_ajax_find_best_candidates', 'handle_find_best_candidates');
add_action('wp_ajax_nopriv_find_best_candidates', 'handle_find_best_candidates');

function handle_find_best_candidates() {
    global $wpdb;

    error_log("AJAX: find_best_candidates called");

    $raw_data = $_POST['data'] ?? '';
    error_log("Raw POST data: " . $raw_data);

    $selections = json_decode(stripslashes($raw_data), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON decode error: " . json_last_error_msg());
        wp_send_json_error(["error" => "Invalid JSON"]);
    }

    error_log("Decoded selections: " . print_r($selections, true));

    $specialization = $selections['specialization'] ?? '';
    $sub_role = $selections['subrole'] ?? '';
    $experience = $selections['experience'] ?? '';
    $availability = $selections['availability'] ?? '';
    $sector = $selections['sector'] ?? '';
    $tools = $selections['tools'] ?? [];

    $table = $wpdb->prefix . "candidates";

    // Start query
    $query = "SELECT * FROM $table WHERE status = 'Approved'";
    $params = [];

    if ($specialization) {
        $query .= " AND specialization = %s";
        $params[] = $specialization;
    }

    if ($sub_role) {
        $query .= " AND sub_role = %s";
        $params[] = $sub_role;
    }

    if ($experience) {
        $query .= " AND experience = %s";
        $params[] = $experience;
    }

    if ($availability) {
        $query .= " AND availability = %s";
        $params[] = $availability;
    }

    if ($sector) {
        $query .= " AND sector = %s";
        $params[] = $sector;
    }

    $query .= " ORDER BY updated_at DESC LIMIT 10";

    error_log("Prepared query: $query");
    error_log("With params: " . print_r($params, true));

    try {
        $results = $wpdb->get_results($wpdb->prepare($query, ...$params), ARRAY_A);
        error_log("DB results: " . print_r($results, true));
    } catch (Exception $e) {
        error_log("DB query error: " . $e->getMessage());
        wp_send_json_error(["error" => "Database error"]);
    }

    // exclude candidates with 0 matching tools
    $results = array_filter($results, function($candidate) use ($tools) {
        $candidateTools = array_map('trim', explode(',', $candidate['lang_tools'] ?? ''));
        return count(array_intersect($tools, $candidateTools)) > 0;
    });

    if (!$results) {
        error_log("No candidates found matching filters.");
        wp_send_json([]);
    }

    // Score based on tools
    foreach ($results as &$candidate) {
        $candidate_tools = explode(',', $candidate['lang_tools'] ?? '');
        $score = count(array_intersect(array_map('trim', $candidate_tools), $tools));
        $candidate['score'] = $score;
    }

    usort($results, fn($a, $b) => $b['score'] <=> $a['score']);
    wp_send_json($results);

}