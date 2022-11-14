$(document).ready(function($) {
    $('.firstNameValidation').hide()
    $('.lastNameValidation').hide()
    $('.emailValidation').hide()
    $('.phoneValidation').hide()
    $('.addressValidation').hide()

    $('.invalidAlert').hide()
    $('.errorAlert').hide()
    $('.successAlert').hide()

    $('#createAgent').click(function () {
        let data = {
            firstName: $('#firstName').val(),
            lastName: $('#lastName').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val()
        }

        /* Create agent */
        $.post(
            "/api/agents",
            {data},
            function(response) {
                $("#agentForm").get(0).reset()
                $(".successAlert").show("slow").delay(3000).hide("slow");

            }).fail(function(response) {
                try {
                    jsonResponse = JSON.parse(response.responseText)
                    for(var key in jsonResponse.errors) {
                        labelKey = key.substring("data.".length)
                        $('.' + labelKey + 'Validation').show()
                    }
                    $(".invalidAlert").show("slow").delay(3000).hide("slow");
                } catch (e) {
                    $(".errorAlert").show("slow").delay(3000).hide("slow");
                }
        })
    })

    $('#firstName').keyup(function() {
        $('.firstNameValidation').hide()
    })

    $('#lastName').keyup(function() {
        $('.lastNameValidation').hide()
    })

    $('#email').keyup(function() {
        $('.emailValidation').hide()
    })

    $('#phone').keyup(function() {
        $('.phoneValidation').hide()
    })

    $('#adddress').keyup(function() {
        $('.addressValidation').hide()
    })
})
