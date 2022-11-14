$('.agentExists').hide()

$(document).ready(function($) {
    $(document).on('click', 'input[type=radio]', function() {
        id = $(this).attr('id')
        if (id.startsWith('selling')) {
            propertyId = id.substring('selling'.length)
        } else {
            propertyId = id.substring('viewing'.length)
        }
        document.getElementsByName('addProperty').forEach(function(property) {
            property.setAttribute('disabled', true)
        })
        $('#addproperty' + propertyId).prop('disabled', false)
    })

    $(document).on('click', 'td input[type=submit]', function() {
        pathArray = window.location.pathname.split('/');
        agentId = pathArray[3]

        id = $(this).attr('id')
        propertyId = id.substring('addproperty'.length)
        selling = $('#selling' + propertyId)

        sellAgent = false
        if (selling.prop('checked') === true) {
            sellAgent = true
        }

        //call api
        $.post("/api/agents/" + agentId + "/properties/" + propertyId,
            {sellAgent: sellAgent},
            function(response) {
                window.location.href = window.location.origin + "/api/agents/" + agentId
            }
        ).fail(function(response) {
            if(response.status === 422) {
                jsonError = JSON.parse(response.responseText)
                if (jsonError.errors.property) {
                    $('.agentExists').show("slow").delay(3000).hide("slow")
                }
            }
        })
    })
})
