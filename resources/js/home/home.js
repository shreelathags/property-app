$(document).ready(function($) {
    $('#searchAgent').click(function() {
        text = $('#searchInput').val()
        $.get(
            "/api/agents/search",
            {text: text},
            function(response) {
                $('#agentList').html(response)
            }
        )
    })

    //Search cleared
    $('#searchInput').click(function() {
        setTimeout(function() {
            if ($('#searchInput').val() == "") {
                $('#searchAgent').prop('disabled', true)
            }
        }, 1)

    })


    $('#searchInput').keyup(function() {
        if($(this).val().length >= 3) {
            $('#searchAgent').prop('disabled', false)
        } else {
            $('#searchAgent').prop('disabled', true)
        }
    })
})
