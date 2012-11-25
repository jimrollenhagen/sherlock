$(document).ready(function(){
  // Clear username input on focus
  $("#su").focus(function(){
    if($(this).val() == "Username") {
      $(this).val("");
    }
  })
  
  // Statistic summary
  $("#statistics_summary .summary_content:gt(0)").hide();
  $("#statistics_summary .section_head_links li a").click(function(){
    $("#statistics_summary .summary_content").fadeOut('fast');
    $("#statistics_summary .section_head_links li").removeClass("active");
    $($(this).attr("href")).fadeIn('fast');
    $("#summary_type").text($(this).text());
    $(this).parent("li").addClass("active");
    return false;
  })
})