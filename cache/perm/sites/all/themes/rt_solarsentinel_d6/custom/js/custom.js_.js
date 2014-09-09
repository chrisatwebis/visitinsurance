$(document).ready(function(){
  var total_days = 0;
  if($("#webform-component-travel-dates--effective-date select").length > 0){
    $("#webform-component-travel-dates--effective-date select").bind("change",show_duration);
    $("#webform-component-travel-dates--expiry-date select").bind("change",show_duration);
    show_duration();
  }

  function get_total_days()
  {
    var all_selected = true;
    $("#webform-component-travel-dates--effective-date select").each(
      function(){
        if($(this).val() == "")
        {
          all_selected = false;
          return all_selected;
        }
      }
    );
    $("#webform-component-travel-dates--expiry-date select").each(
      function(){
        if($(this).val() == "")
        {
          all_selected = false;
          return all_selected;
        }
      }
    );
    return all_selected;
  }
  
  function show_duration()
  {
    if(get_total_days())
    {
      var effective_year = $("#edit-submitted-travel-dates-effective-date-year").val();
      var effective_day = $("#edit-submitted-travel-dates-effective-date-day").val();
      var effective_month = $("#edit-submitted-travel-dates-effective-date-month").val();
      
      var expiry_month = $("#edit-submitted-travel-dates-expiry-date-month").val();
      var expiry_day = $("#edit-submitted-travel-dates-expiry-date-day").val();
      var expiry_year = $("#edit-submitted-travel-dates-expiry-date-year").val();
      
      var minutes = 1000*60;
      var hours = minutes*60;
      var days = hours*24;
      var expiry_date = new Date();
      expiry_date.setFullYear(expiry_year,expiry_month-1,expiry_day);
      var effective_date = new Date();
      effective_date.setFullYear(effective_year,effective_month-1,effective_day);
      var t = expiry_date.getTime() - effective_date.getTime();
      total_days = Math.round(t/days)+1;
      
      if($("#duration_days").length)
      {
        $("#duration_days #duration").html(Drupal.t("Duration")+": <b>"+total_days+Drupal.t("days")+" </b>");
      }else{
        $("#webform-component-travel-dates--expiry-date").append('<span id="duration_days" style="position:absolute;"><span style="position: relative; top: 3px; visibility: visible; padding: 2px;margin-left:10px; background-color: rgb(221, 255, 221); border: 1px dashed rgb(72, 165, 72);" id="duration">'+Drupal.t("Duration")+': <b>'+total_days+Drupal.t("days")+' </b></span></span>');
      }
      
      sum_insured_dedutible_check();
    }else{
      if($("#duration_days").length)
      {
        $("#duration_days").remove();
      }
    }
    
  }
  
  //Check Whether should show $3000 dedutible.
  sum_insured_dedutible_check();
  $("#webform-component-insurable-members--coverage-and-deductible--sum-insured select").change(
    function()
    {
      sum_insured_dedutible_check();
    }
  );
    
  Drupal.behaviors.togglesuminsured = function (context){
    sum_insured_dedutible_check();
    $("#webform-component-insurable-members--coverage-and-deductible--sum-insured select").change(
      function()
      {
        sum_insured_dedutible_check();
      }
    )
  };

  //This function is to make sure the dedutible 3000CAD option is going to show up only when the sum_insured is 100000 and the duration is over 365 days.
  function sum_insured_dedutible_check()
  {	
    var nid = $("#edit-submitted-insurance-product-1 option:selected").val();
    if($("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option:selected").val() == 3000)
    {
      if( ($("#webform-component-insurable-members--coverage-and-deductible--sum-insured select").val() == 100000 && total_days >365) || nid == 83 || nid == 77)
      {
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='3000']").css("display","block");
      }else{
        //remove 3000 option.
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='3000']").css("display","none");
      }
      
      $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select").html($("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select").html());
    }else{
    
      if( ($("#webform-component-insurable-members--coverage-and-deductible--sum-insured select").val() == 100000 && total_days >365) || nid == 83 || nid == 77)
      {
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='3000']").css("display","block");
      }else{
        //remove 3000 option.
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='3000']").css("display","none");
      }
    }
    
    
    //deductible 1000 condition
    //If this is : tic jf
    if(nid == 66){
      if( ($("#webform-component-insurable-members--coverage-and-deductible--sum-insured select").val() >= 100000 && total_days >365) )
      {
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='1000']").css("display","block");
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='3000']").css("display","block");
      }else{
        //remove 1000 option.
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='1000']").css("display","none");
        $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='3000']").css("display","none");
      }
      $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select").val("0").change();
    }
    
    //deductible 1000 condition
    //If this is : tic jf
    if(nid == 1581){
      
        if( ($("#webform-component-insurable-members--coverage-and-deductible--sum-insured select").val() == 150000) )
        {
          $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='0']").css("display","block");
          $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='50']").css("display","none");
          $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select").val("0").change();
        }else{
          //remove 1000 option.
          $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='0']").css("display","none");
          $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select option[value='50']").css("display","block");          
          $("#webform-component-insurable-members--coverage-and-deductible--select-a-deductible-amount select").val("50").change();
        }

    }

  }
  
  //Insured Birth day
  
  Drupal.behaviors.toggleinsureddob = function (context){

  
    $.each([1,2,3,4,5], function(index, value) { 
      $("#webform-component-insurable-members--insured-"+value+"--birth-date select").bind("change",{element:value},show_age);
      $("#webform-component-insurable-members--insured-"+value+"--birth-date select").change();
    });
  };
  
  //Initilize the default behavior when the page is loaded.		
  //Since the dob field is well named: ***1***,***2***, so , we can use 1,2,3,4,5 as delta.		
  $.each([1,2,3,4,5], function(index, value) { 
    $("#webform-component-insurable-members--insured-"+value+"--birth-date select").bind("change",{element:value},show_age);
    $("#webform-component-insurable-members--insured-"+value+"--birth-date select").change();
  });
  
  function show_age(event)
  {
    var delta = event.data.element;
    if(birth_date_all_selected(delta))
    {
      var dob_year = $("#edit-submitted-insurable-members-insured-"+delta+"-birth-date-year").val();
      var dob_day = $("#edit-submitted-insurable-members-insured-"+delta+"-birth-date-day").val();
      var dob_month = $("#edit-submitted-insurable-members-insured-"+delta+"-birth-date-month").val();
        
      var minutes = 1000*60;
      var hours = minutes*60;
      var days = hours*24;
      
      var dob_date = new Date();
      dob_date.setFullYear(dob_year,dob_month-1,dob_day);
      
      var current_date = new Date();
      
      var insured_1_age = 0;
      
      var diff = current_date - dob_date;

      var diffdays = diff / days;
            
      var insured_1_age = Math.floor(diffdays / 365.25);
      
      if($("#webform-component-insurable-members--insured-"+delta+" .insured_age").length)
      {
        $("#webform-component-insurable-members--insured-"+delta+" .insured_age .insured_age_duration").html(Drupal.t("Age")+": <b>"+insured_1_age+Drupal.t("year old")+" </b>");
      }else{
        $("#webform-component-insurable-members--insured-"+delta+"").append('<span class="insured_age" style="position:absolute;right:0px;top:0px"><span style="position: relative; top: 3px; visibility: visible; padding: 2px;margin-left:10px; background-color: rgb(221, 255, 221); border: 1px dashed rgb(72, 165, 72);" class="insured_age_duration">'+Drupal.t("Age")+': <b>'+insured_1_age+Drupal.t("year old")+' </b></span></span>');
      }
      
    }else{
      if($("#webform-component-insurable-members--insured-"+delta+" .insured_age").length)
      {
        $("#webform-component-insurable-members--insured-"+delta+" .insured_age").remove();
      }
    }
  }
  function birth_date_all_selected(delta)
  {
    var all_selected = true;
    $("#webform-component-insurable-members--insured-"+delta+"--birth-date select").each(
      function(){
        if($(this).val() == "")
        {
          all_selected = false;
          return all_selected;
        }
      }
    );
    
    return all_selected;
  }
});