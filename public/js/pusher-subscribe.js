const Http = window.axios
const Echo = window.Echo
const message = $("#message")

$('#send-notification').on('click', function() {
    Http.post("{{ url('send') }}", {
        'message' : message.val()
    }).then(() => {
        message.val("")
    })
})

let channel = Echo.channel('channel-newreseller')
channel.listen('NewResellerEvent', function(data) {
    toast(true, data.message.message)
})