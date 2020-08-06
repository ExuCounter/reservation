$(document).ready(function () {

  /********************************************
  * Set current date for signature
  ********************************************/
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; 
  var yyyy = today.getFullYear();
  if(dd<10) { dd='0'+dd;}
  if(mm<10){ mm='0'+mm;} 
  today = dd+'/'+mm+'/'+yyyy;
  $('#datepick').val(today);
  
  /********************************************
  * Remove Error after checkbox checked
  ********************************************/
  $('input:checkbox').click(function() {
     $(this).parents('tr').find('input:checkbox').prop('checked', false);
     $(this).prop('checked', true);
     clearValidation('#questionOne')
     clearValidation('#questionTwo')
     clearValidation('#questionThree')
     clearValidation('#questionFour')
     clearValidation('#questionFive')
  });

  /********************************************
  * Reset Validation
  ********************************************/
  var fields = ['#questionOne', '#questionTwo', '#questionThree', '#questionFour', '#questionFive', '#firstName', '#lastName', '#idNumber', '#fullAddress', '#datepick', '#signImg','#idNumberValidateError'];
  function resetValidation(){
    $.each(fields, function(index, field){
        clearValidation(field);
     })
  }

  $('input').keyup(function(){
    resetValidation();
  })

  /********************************************
  * Handle Submit Button
  ********************************************/
  $('#submitButton').click(function(){
     if(!$('#success-alert').hasClass('d-none'))
        $('#success-alert').addClass('d-none')
      resetValidation()
     if(!$('#questionOneYes').is(":checked") && !$('#questionOneNo').is(":checked"))
        return displayRequiredError("#questionOne")
     if(!$('#questionTwoYes').is(":checked") && !$('#questionTwoNo').is(":checked"))
        return displayRequiredError("#questionTwo")
     if(!$('#questionThreeYes').is(":checked") && !$('#questionThreeNo').is(":checked"))
        return displayRequiredError("#questionThree")
     if(!$('#questionFourYes').is(":checked") && !$('#questionFourNo').is(":checked"))
        return displayRequiredError("#questionFour")
     if(!$('#questionFiveYes').is(":checked") && !$('#questionFiveNo').is(":checked"))
        return displayRequiredError("#questionFive")
     if(!$('#firstName').val() || !$('#firstName').val().length)
        return displayRequiredError("#firstName")
     if(!$('#lastName').val() || !$('#lastName').val().length)
        return displayRequiredError("#lastName")
     if(!$('#idNumber').val() || !$('#idNumber').val().length)
        return displayRequiredError("#idNumber")
     if(isNaN($('#idNumber').val()))
        return displayRequiredError("#idNumberValidateError")
     if(!$('#fullAddress').val() || !$('#fullAddress').val().length)
        return displayRequiredError("#fullAddress")
     if(!$('#datepick').val() || !$('#datepick').val().length)
        return displayRequiredError("#datepick")
     if(!signatureImg || !signatureImg.length)
        return displayRequiredError("#signImg")
     var data={
        q1:$('#questionOneYes').is(":checked") ? 'Yes' : 'No',
        q2:$('#questionTwoYes').is(":checked") ? 'Yes' : 'No',
        q3:$('#questionThreeYes').is(":checked") ? 'Yes' : 'No',
        q4:$('#questionFourYes').is(":checked") ? 'Yes' : 'No',
        q5:$('#questionFiveYes').is(":checked") ? 'Yes' : 'No',
        firstName:$('#firstName').val(),
        lastName:$('#lastName').val(),
        idNumber:$('#idNumber').val(),
        fullAddress:$('#fullAddress').val(),
        datepick:$('#datepick').val(),
        signatureImg:signatureImg,
     }
     
     $('#submitButton').attr('disabled', true);
     $('.loader').removeClass('d-none');
     $('#form-box')[0].reset();
     $('#signImg').find('img').remove();
     renderSignature()

     // POST request to server
     var jqxhr = $.post( "api/covid19.php",data, function() {
        $('#success-alert').removeClass('d-none');
        $('#submitButton').attr('disabled', false);
        $('.loader').addClass('d-none');
        signatureImg = '';
     }).fail(function() {
        $('.loader').addClass('d-none');
        $('#submitButton').attr('disabled', false);
        signatureImg = '';
     });
  })

  function clearValidation(element){
     if(element.search('Error') == -1)
        $(element+'Error').addClass('d-none');
     else
        $(element).addClass('d-none');
  }

  function displayRequiredError(element){
     if(element.search('Error') == -1)
        $(element+'Error').removeClass('d-none');
     else
        $(element).removeClass('d-none');
     $([document.documentElement, document.body]).animate({
        scrollTop: $(element).offset().top - 170
     }, 700);
  }

  var signatureImg = '';
  function renderSignature(){
    var sign = $('#txt').SignaturePad({
        allowToSign: true,
        img64: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7',
        border: '1px solid #c7c8c9',
        width: '250px',
        height: '100px',
        callback: function (data, action) {
          if(data){
            resetValidation();
            signatureImg = data;
          }else{
            signatureImg = '';
          }
        }
    });
  }
  renderSignature();
})