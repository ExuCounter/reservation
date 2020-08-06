$(document).ready(function () {

  /********************************************
  * Header Menu Configuration
  ********************************************/
  $(".menu-mobile-effect").click(function(){
    $('.wrapper-container').toggleClass('mobile-menu-open');
  })

  /********************************************
  * Set current date for signature
  ********************************************/
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1; 
  var yyyy = today.getFullYear();
  if(dd<10) {dd='0'+dd;}
  if(mm<10){mm='0'+mm;}
  today = dd+'/'+mm+'/'+yyyy;
  $('#datepick3').val(today);

  /********************************************
  * On Resize responsive card number
  ********************************************/
  $(window).on('resize', function(){
    $('#cardNumber').css({'background-position':'3px -168px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
  })

  /********************************************
  * Custom Collapse Toggle Method
  ********************************************/
  $('.toggle-collapse').click(function(){
    var collapse = $(this).parents('.card').find('.collapse'),
        child = $(this).children('.collapse-icon');
    collapse.collapse('toggle')
  })
  $('.collapse').on('show.bs.collapse', function () {
    child = $(this).parents('.card').find('.collapse-icon');
    child.addClass('fa fa-minus');
  })
  $('.collapse').on('hide.bs.collapse', function () {
    child = $(this).parents('.card').find('.collapse-icon');
    child.addClass('fa fa-plus');
  })
  $('.js-scroll-trigger').click(function(){
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#exItem1Header").offset().top - 60
     }, 700);
  })

  /********************************************
  * Check Card Type isracard
  ********************************************/
  function isracardCheck(num) {
    if(typeof num !== 'number') num=''+num;
    if(num.length < 8 || num.length > 9) return false;
    var sum=0;
      num.split('').forEach(function(val,key){
      sum+=parseInt(val,10)*(num.length-key);
    })
    return sum % 11 == 0;
  }

  /********************************************
  * Validate credit card
  ********************************************/
  var isValidateCard = false,
      isIsraCard = false;
  $('#cardNumber').on('input',function(){
      resetCreditCardValidations();

      // check validation for isracard
      if(isracardCheck($('#cardNumber').val())){
        isValidateCard = true;
        isIsraCard = true;
        $(this).css({'background-position':'3px -503px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
      }
      $(this).validateCreditCard(function(result) {
          isValidateCard = result.valid;
          if(result.card_type && result.card_type.name == 'visa'){
            if(isValidateCard)
              $(this).css({'background-position':'3px -215px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
            else
              $(this).css({'background-position':'3px -215px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
          }else if(result.card_type && result.card_type.name == 'visa_electron'){
            if(isValidateCard)
              $(this).css({'background-position':'3px -215px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
            else
              $(this).css({'background-position':'3px -215px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
          }else if(result.card_type && result.card_type.name == 'maestro'){
            if(isValidateCard)
              $(this).css({'background-position':'3px -307px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
            else
              $(this).css({'background-position':'3px -307px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
          }else if(result.card_type && result.card_type.name == 'mastercard'){
            if(isValidateCard)
              $(this).css({'background-position':'3px -307px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
            else
              $(this).css({'background-position':'3px -307px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
          }else if(result.card_type && result.card_type.name == 'discover'){
            if(isValidateCard)
              $(this).css({'background-position':'3px -400px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
            else
              $(this).css({'background-position':'3px -400px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
          }else if(result.card_type && result.card_type.name == 'amex'){
            if(isValidateCard)
              $(this).css({'background-position':'3px -434px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
            else
              $(this).css({'background-position':'3px -434px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
          }else if(result.card_type && (result.card_type.name == 'diners_club_carte_blanche' || result.card_type.name == 'diners_club_international')){
            if(isValidateCard)
              $(this).css({'background-position':'3px -468px,'+($('#cardNumber').outerWidth()-25)+'px'+' -98px'})
            else
              $(this).css({'background-position':'3px -468px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
          }else if(isIsraCard){
            isValidateCard = true;
          }
      });
  })

  /********************************************
  * Reset credit card validation
  ********************************************/
  function resetCreditCardValidations(){
      isValidateCard = false;
      isIsraCard = false;
      $('#cardNumber').css({'background-position':'3px -168px,'+($('#cardNumber').outerWidth()-25)+'px'+' -75px'})
  }

  /********************************************
  * Remove Error after checkbox checked
  ********************************************/
  $('input:checkbox').click(function() {
     $(this).parents('.roomType').find('input:checkbox').prop('checked', false);
     if($(this).hasClass('rmtype')){
       if(!$('#roomTypeError').hasClass('d-none'))
          $('#roomTypeError').addClass('d-none');
       $(this).prop('checked', true);
     }else if($(this).hasClass('plcy')){
       if(!$('#policyError').hasClass('d-none'))
          $('#policyError').addClass('d-none');
     }
  });

  /********************************************
  * Clear form validations
  ********************************************/
  function clearAllValidations(){
      // reset validation
     var fields = ['#firstName', '#lastName', '#mobileNumber', '#mailAddress', '#roomType', '#adult', '#children', '#datepick2', '#datepick', '#orderAmount', '#cardHolderName', '#cardId', '#cardNumber', '#expiryYear', '#expiryMonth', '#cvv', '#policy', '#signTxt', '#datepick3', '#signImg', '#mobileNumberValidate','#adultValidateError', '#mailAddressValidateError', '#childrenValidateError', '#orderAmountValidateError', '#cardNumberValidateError', '#cardIdValidateError', '#datepickValidateError', '#cvvValidateError','#addressError', '#cityError', '#cvvMissingField'];
     $.each(fields, function(index, field){
        clearValidation(field);
     })
  }

  /********************************************
  * check validation
  ********************************************/
  function checkValidation(moveToTop){
    var firstName = $('#firstName').val(),
         lastName = $('#lastName').val(),
         mobileNumber = $('#mobileNumber').val(),
         mailAddress = $('#mailAddress').val(),
         roomTypeOne = $('#roomTypeOne').is(":checked"),
         roomTypeTwo = $('#roomTypeTwo').is(":checked"),
         policy = $('#policy').is(":checked"),
         adult = $('#adult').val(),
         children = $('#children').val(),
         arrivalDate = $('#datepick2').val(),
         depatureDate = $('#datepick').val(),
         signDate = $('#datepick3').val(),
         orderAmount = $('#orderAmount').val(),
         cardHolderName = $('#cardHolderName').val(),
         cardId = $('#cardId').val(),
         cardNumber = $('#cardNumber').val(),
         expiryYear = $('#expiryYear').val(),
         expiryMonth = $('#expiryMonth').val(),
         cvv = $('#cvv').val(),
         message = $('#message').val(),
         address = $('#address').val(),
         city = $('#city').val(),
         signTxt = $('#signTxt').val();
     firstName = firstName.trim();
     lastName = lastName.trim();
     mailAddress = mailAddress.trim();
     mobileNumber = mobileNumber.trim();
     adult = adult.trim();
     children = children.trim();
     arrivalDate = arrivalDate.trim();
     depatureDate = depatureDate.trim();
     orderAmount = orderAmount.trim();
     cardHolderName = cardHolderName.trim();
     cardId = cardId.trim();
     cardNumber = cardNumber.trim();
     expiryYear = expiryYear.trim();
     expiryMonth = expiryMonth.trim();
     cvv = cvv.trim();
     signTxt = signTxt.trim();
     signDate = signDate.trim();
     address = address.trim();
     city = city.trim();
     /************************************
     * Validate form field
     *************************************/
     if(!firstName || !firstName.length)
        return displayError('#firstName',moveToTop);
     if(!lastName || !lastName.length)
        return displayError('#lastName',moveToTop);
     if(!mobileNumber || !mobileNumber.length)
        return displayError('#mobileNumber',moveToTop);
     if(isNaN(mobileNumber) || mobileNumber.length != 10)
        return displayError('#mobileNumberValidateError',moveToTop);
     if(!mailAddress || !mailAddress.length)
        return displayError('#mailAddress',moveToTop);
     if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mailAddress)))
        return displayError('#mailAddressValidateError',moveToTop);
     if(!city || !city.length)
        return displayError('#city',moveToTop);
     if(!address || !address.length)
        return displayError('#address',moveToTop);
     if(roomTypeOne == false && roomTypeTwo == false)
        return displayError('#roomType',moveToTop);
     if(!adult || !adult.length)
        return displayError('#adult',moveToTop);
     if(isNaN(adult))
        return displayError('#adultValidateError',moveToTop);
     if(!children || !children.length)
        return displayError('#children',moveToTop);
     if(isNaN(children))
        return displayError('#childrenValidateError',moveToTop);
     if(!arrivalDate || !arrivalDate.length)
        return displayError('#datepick2',moveToTop);
     if(!depatureDate || !depatureDate.length)
        return displayError('#datepick',moveToTop);
      var depatureDatePattern = /(\d+)\/(\d+)\/(\d+)/.exec(depatureDate),
         depatureFull = new Date(depatureDatePattern[3], depatureDatePattern[2], depatureDatePattern[1]),
         arrivalDatePattern = /(\d+)\/(\d+)\/(\d+)/.exec(arrivalDate),
         arrivalFull = new Date(arrivalDatePattern[3], arrivalDatePattern[2], arrivalDatePattern[1]);
     if(Date.parse(arrivalFull) > Date.parse(depatureFull) || Date.parse(depatureFull)==Date.parse(arrivalFull))
       return displayError('#datepickValidateError',moveToTop);
     if(!orderAmount || !orderAmount.length)
        return displayError('#orderAmount',moveToTop);
     if(isNaN(orderAmount))
        return displayError('#orderAmountValidateError',moveToTop);
     if(!cardHolderName || !cardHolderName.length)
        return displayError('#cardHolderName',moveToTop);
     if(!cardId || !cardId.length)
        return displayError('#cardId',moveToTop);
      if(isNaN(cardId))
        return displayError('#cardIdValidateError',moveToTop);
     if(!cardNumber || !cardNumber.length)
        return displayError('#cardNumber',moveToTop);
     if(!isValidateCard)
        return displayError('#cardNumberValidateError',moveToTop);
     if(!expiryYear || !expiryYear.length)
        return displayError('#expiryYear',moveToTop);
     if(!expiryMonth || !expiryMonth.length)
        return displayError('#expiryMonth',moveToTop);
     if(!cvv || !cvv.length)
        return displayError('#cvv',moveToTop);
      if(isNaN(cvv))
        return displayError('#cvvValidateError',moveToTop);
      if(cvv.length <= 2)
        return displayError('#cvvMissingFieldError',moveToTop);
     if(policy == false)
        return displayError('#policy',moveToTop);
     if(!signTxt || !signTxt.length)
        return displayError('#signTxt',moveToTop);
     if(!signDate || !signDate.length)
        return displayError('#datepick3',moveToTop);
     if(!signatureImg || !signatureImg.length)
        return displayError('#signImg',moveToTop);
     return true;
  }

  /********************************************
  * remove validation when user typing
  ********************************************/
  $('input').keyup(function(){
    clearAllValidations();
  })
  $('#datepick2').change(function(){
    clearAllValidations();
  })
  $('#datepick').change(function(){
    clearAllValidations();
  })
  $('select').change(function(){
    clearAllValidations();
  })

  /********************************************
  * Handle submit event
  ********************************************/
  $('#submitButton').click(function(){
     if(!$('#success-alert').hasClass('d-none'))
        $('#success-alert').addClass('d-none')
     if(!$('#error-alert').hasClass('d-none'))
        $('#error-alert').addClass('d-none')
      clearAllValidations();
      if(checkValidation(true) != true)
        return;
     // configure form fields
     var firstName = $('#firstName').val(),
         lastName = $('#lastName').val(),
         mobileNumber = $('#mobileNumber').val(),
         mailAddress = $('#mailAddress').val(),
         roomTypeOne = $('#roomTypeOne').is(":checked"),
         roomTypeTwo = $('#roomTypeTwo').is(":checked"),
         policy = $('#policy').is(":checked"),
         adult = $('#adult').val(),
         children = $('#children').val(),
         arrivalDate = $('#datepick2').val(),
         depatureDate = $('#datepick').val(),
         signDate = $('#datepick3').val(),
         orderAmount = $('#orderAmount').val(),
         cardHolderName = $('#cardHolderName').val(),
         cardId = $('#cardId').val(),
         cardNumber = $('#cardNumber').val(),
         expiryYear = $('#expiryYear').val(),
         expiryMonth = $('#expiryMonth').val(),
         cvv = $('#cvv').val(),
         message = $('#message').val(),
         address = $('#address').val(),
         city = $('#city').val(),
         signTxt = $('#signTxt').val();
     firstName = firstName.trim();
     lastName = lastName.trim();
     mailAddress = mailAddress.trim();
     mobileNumber = mobileNumber.trim();
     adult = adult.trim();
     children = children.trim();
     arrivalDate = arrivalDate.trim();
     depatureDate = depatureDate.trim();
     orderAmount = orderAmount.trim();
     cardHolderName = cardHolderName.trim();
     cardId = cardId.trim();
     cardNumber = cardNumber.trim();
     expiryYear = expiryYear.trim();
     expiryMonth = expiryMonth.trim();
     cvv = cvv.trim();
     signTxt = signTxt.trim();
     signDate = signDate.trim();
     address = address.trim();
     city = city.trim();
     callDetailsCheckBox = $('#callDetails').is(":checked");
     $('#submitButton').attr('disabled', true);
     $('.loader').removeClass('d-none');
     $('#form-box')[0].reset();
     $('#signImg').find('img').remove();
     renderSignature();
     var data = {
        firstName:firstName,
        lastName:lastName,
        mobileNumber:mobileNumber,
        mailAddress:mailAddress,
        address:address,
        city:city,
        roomTypeOne:roomTypeOne,
        roomTypeTwo:roomTypeTwo,
        policy:policy,
        orderAmount:orderAmount,
        adult:adult,
        children:children,
        arrivalDate:arrivalDate,
        depatureDate:depatureDate,
        cardHolderName:cardHolderName,
        cardId:cardId,
        cardNumber:cardNumber,
        expiryYear:expiryYear,
        expiryMonth:expiryMonth,
        cvv:cvv,
        signTxt:signTxt,
        signatureImg:signatureImg,
        message:message,
        callDetails:callDetailsCheckBox == true ? 'כן' : 'לא',
        signDate:signDate
     }

     // POST request to server
     var jqxhr = $.post( "api/reservation.php",data, function() {
         resetCreditCardValidations();
        $('#success-alert').removeClass('d-none');
        $('#submitButton').attr('disabled', false);
        $('.loader').addClass('d-none');
        signatureImg = '';
     }).fail(function() {
         resetCreditCardValidations();
         signatureImg = '';
        $('.loader').removeClass('d-none');
        $('#error-alert').removeClass('d-none');
     });
  })
  function clearValidation(element){
     if(element.search('Error') == -1)
        $(element+'Error').addClass('d-none');
     else
        $(element).addClass('d-none');
  }
  function displayError(element, moveToTop){
     if(element.search('Error') == -1)
        $(element+'Error').removeClass('d-none');
     else
        $(element).removeClass('d-none');
      if(moveToTop){ 
         $([document.documentElement, document.body]).animate({
            scrollTop: $(element).offset().top - 170
         }, 700);
      }
  }

  /********************************************
  * Handle signature pad
  ********************************************/
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
              clearAllValidations();
              signatureImg = data;
            }else{
              signatureImg = '';
            }
          }
      });
  }
  renderSignature();
})