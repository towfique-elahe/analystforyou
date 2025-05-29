jQuery(document).ready(function($) {
  // ✅ Show modal on page load
  $("#filter-modal").fadeIn();

  // ✅ Show modal on trigger button click
  $("#open-filter-btn").click(function() {
    $("#filter-modal").fadeIn();
  });

  // ✅ Close modal on ❌ click
  $(".close-modal").click(function() {
    $("#filter-modal").fadeOut();
  });

  // Step-by-step filtering
  $(".next-step").click(function() {
    $(this).closest(".step").hide().next(".step").fadeIn();
  });

  // Submit filters
  $("#apply-filters").click(function() {
    let filters = {
      skills: [],
      experience: [],
      location: [],
      availability: [],
      industry: [],
    };

    $('input[name="skills[]"]:checked').each(function(){ filters.skills.push($(this).val()); });
    $('input[name="years-of-experience[]"]:checked').each(function(){ filters.experience.push($(this).val()); });
    $('input[name="location-preference[]"]:checked').each(function(){ filters.location.push($(this).val()); });
    $('input[name="availability[]"]:checked').each(function(){ filters.availability.push($(this).val()); });
    $('input[name="industry-expertise[]"]:checked').each(function(){ filters.industry.push($(this).val()); });

    $.post(ajax_vars.ajaxurl, {
      action: 'filter_candidates',
      filters: filters
    }, function(response) {
      $("#candidate-list").html(response);
      $("#filter-modal").fadeOut();
    });
  });
});
