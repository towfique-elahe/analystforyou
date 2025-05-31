<?php

// job listings section
function custom_job_listing_shortcode() {
    ob_start();
?>

<div id="jobListing">
    <div class="container">
        <!-- filter sidebar -->
        <div class="col sidebar">
            <div class="sidebar-header">
                <h3 class="sidebar-heading">
                    Advance Filter
                </h3>
                <a href="javascript:void()" class="reset-button">
                    Reset
                </a>
            </div>
            <div class="filter-group">
                <div class="search">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" name="search" id="search" placeholder="Search jobs...">
                </div>
            </div>
            <div class="filter-group">
                <h4 class="filter-heading">
                    Skill
                </h4>
                <div class="filter-items">
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Skill 1</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Skill 2</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Skill 3</label>
                    </div>
                </div>
            </div>
            <div class="filter-group">
                <h4 class="filter-heading">
                    Experience
                </h4>
                <div class="filter-items">
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Experience 1</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Experience 2</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Experience 3</label>
                    </div>
                </div>
            </div>
            <div class="filter-group">
                <h4 class="filter-heading">
                    Location
                </h4>
                <div class="filter-items">
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Location 1</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Location 2</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Location 3</label>
                    </div>
                </div>
            </div>
            <div class="filter-group">
                <h4 class="filter-heading">
                    Availability
                </h4>
                <div class="filter-items">
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Availability 1</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Availability 2</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Availability 3</label>
                    </div>
                </div>
            </div>
            <div class="filter-group">
                <h4 class="filter-heading">
                    Industry
                </h4>
                <div class="filter-items">
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Industry 1</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Industry 2</label>
                    </div>
                    <div class="filter-item">
                        <input type="checkbox" name="" id=""><label for="">Industry 3</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- job openings content -->
        <div class="col content">
            <!-- header -->
            <div class="row content-header">
                <div class="col">
                    <p class="showing-text">
                        Showing <strong>41</strong>-<strong>60</strong> of <strong>944</strong> jobs
                    </p>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="job-count">
                            Show:
                            <select name="" id="">
                                <option value="">6</option>
                                <option value="">9</option>
                                <option value="">12</option>
                            </select>
                        </div>
                        <div class="sort-by">
                            Sort by:
                            <select name="" id="">
                                <option value="">Newest Jobs</option>
                                <option value="">Oldest Jobs</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- jobs -->
            <div class="jobs">
                <div class="job">
                    <div class="head">
                        <img src="https://analystforyou.orbit570.com/wp-content/uploads/2025/05/guerrillabuzz-NwD_UggDGs-unsplash-300x169.jpg"
                            alt="" class="featured-image">
                    </div>
                    <div class="body">
                        <h3>
                            <a href="" class="title">Job Title 1</a>
                        </h3>
                        <div class="skills">
                            <span class="skill">AI</span>
                            <span class="skill">Big Data</span>
                            <span class="skill">Cybersecurity</span>
                            <span class="skill">SQL</span>
                        </div>
                        <div class="specs">
                            <p class="spec"><strong>Experience:</strong> 3-5 Years</p>
                            <p class="spec"><strong>Location Preference:</strong> On-Site</p>
                            <p class="spec"><strong>Availability:</strong> Fixed Contact</p>
                            <p class="spec"><strong>Industry Expertise:</strong> Government, Insurance</p>
                        </div>
                    </div>
                </div>
                <div class="job">
                    <div class="head">
                        <img src="https://analystforyou.orbit570.com/wp-content/uploads/2025/05/guerrillabuzz-NwD_UggDGs-unsplash-300x169.jpg"
                            alt="" class="featured-image">
                    </div>
                    <div class="body">
                        <h3>
                            <a href="" class="title">Job Title 2</a>
                        </h3>
                        <div class="skills">
                            <span class="skill">AI</span>
                            <span class="skill">Big Data</span>
                            <span class="skill">Cybersecurity</span>
                            <span class="skill">SQL</span>
                        </div>
                        <div class="specs">
                            <p class="spec"><strong>Experience:</strong> 3-5 Years</p>
                            <p class="spec"><strong>Location Preference:</strong> On-Site</p>
                            <p class="spec"><strong>Availability:</strong> Fixed Contact</p>
                            <p class="spec"><strong>Industry Expertise:</strong> Government, Insurance</p>
                        </div>
                    </div>
                </div>
                <div class="job">
                    <div class="head">
                        <img src="https://analystforyou.orbit570.com/wp-content/uploads/2025/05/guerrillabuzz-NwD_UggDGs-unsplash-300x169.jpg"
                            alt="" class="featured-image">
                    </div>
                    <div class="body">
                        <h3>
                            <a href="" class="title">Job Title 3</a>
                        </h3>
                        <div class="skills">
                            <span class="skill">AI</span>
                            <span class="skill">Big Data</span>
                            <span class="skill">Cybersecurity</span>
                            <span class="skill">SQL</span>
                        </div>
                        <div class="specs">
                            <p class="spec"><strong>Experience:</strong> 3-5 Years</p>
                            <p class="spec"><strong>Location Preference:</strong> On-Site</p>
                            <p class="spec"><strong>Availability:</strong> Fixed Contact</p>
                            <p class="spec"><strong>Industry Expertise:</strong> Government, Insurance</p>
                        </div>
                    </div>
                </div>
                <div class="job">
                    <div class="head">
                        <img src="https://analystforyou.orbit570.com/wp-content/uploads/2025/05/guerrillabuzz-NwD_UggDGs-unsplash-300x169.jpg"
                            alt="" class="featured-image">
                    </div>
                    <div class="body">
                        <h3>
                            <a href="" class="title">Job Title 4</a>
                        </h3>
                        <div class="skills">
                            <span class="skill">AI</span>
                            <span class="skill">Big Data</span>
                            <span class="skill">Cybersecurity</span>
                            <span class="skill">SQL</span>
                        </div>
                        <div class="specs">
                            <p class="spec"><strong>Experience:</strong> 3-5 Years</p>
                            <p class="spec"><strong>Location Preference:</strong> On-Site</p>
                            <p class="spec"><strong>Availability:</strong> Fixed Contact</p>
                            <p class="spec"><strong>Industry Expertise:</strong> Government, Insurance</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- pagination -->
            <div class="pagination">
                <a href="" class="prev">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
                <a href="" class="page active">1</a>
                <a href="" class="page">2</a>
                <a href="" class="page">3</a>
                <a href="" class="page">4</a>
                <a href="" class="next">
                    <ion-icon name="chevron-forward-outline"></ion-icon>
                </a>
            </div>

        </div>
    </div>
</div>

<?php
    return ob_get_clean();
}

add_shortcode( 'custom_job_listing', 'custom_job_listing_shortcode' );