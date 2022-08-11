<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-datatables/custom.jquery.dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendors/fontawesome/all.min.js') }}"></script>
<script src="{{ asset('js/mazer.js') }}"></script>
<script src="{{ asset('vendors/choices.js/choices.min.js') }}"></script>
<script src="{{ asset('vendors/toastify/toastify.js') }}"></script>
<script src="{{ asset('vendors/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/jquery.mask.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('js/jquery.magnific-popup.js') }}"></script>
<script src="{{ asset('js/datatable_indonesian_language.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script type="text/javascript">
    let csrfToken = $('meta[name=csrf-token]').attr('content')

    let toast = (success, message, duration = 2000) => {
        Toastify({
            text: message,
            duration: duration,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: success ? "#4fbe87" : "#f47174",
        }).showToast()
    }

    @if(session()->has('success'))
        setTimeout(function() {
            Toastify({
                text: "{{ session()->get('success') }}",
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#4fbe87",
            }).showToast();
        }, 500);
        @endif

        @if($errors->any())
        setTimeout(function() {
            Toastify({
                text: "Terdapat input yang tidak valid, silahkan coba lagi.",
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#f47174",
            }).showToast();
        }, 500);
        @endif

        @if(session()->has('error'))
        setTimeout(function() {
            Toastify({
                text: "{{ session()->get('error') }}",
                duration: 5000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#f47174",
            }).showToast();
        }, 500);
        @endif

    $(document).ready(function() {
        $('.money').mask('000.000.000', {reverse: true})

        $('.select2').select2()

        let imagePreview = (input) => {
            let imagePreview = $('.image-preview')
            let imagePreviewSpace = $('.image-preview-spacer')

            if (input.files && input.files[0]) {
                var reader = new FileReader()

                reader.onload = function (e) {
                    imagePreviewSpace.show()
                    imagePreview.attr('src', e.target.result)
                }

                reader.readAsDataURL(input.files[0])
            } else {
                imagePreviewSpace.hide()
                imagePreview.attr('src', '')
            }
        }

        let imagePreviewEdit = (input) => {
            let imagePreviewOld = $('.image-preview-old')
            let imagePreviewNew = $('.image-preview-new')
            let imagePreviewSpace = $('.image-preview-spacer')

            if (input.files && input.files[0]) {
                var reader = new FileReader()

                reader.onload = function (e) {
                    imagePreviewOld.hide()
                    imagePreviewSpace.show()
                    imagePreviewNew.attr('src', e.target.result)
                }
                
                reader.readAsDataURL(input.files[0])
            } else {
                imagePreviewOld.show()
                imagePreviewSpace.hide()
                imagePreviewNew.attr('src', '')
            }
        }

        $(".image-preview-input").change(function(){
            imagePreview(this)
        })

        $(".image-preview-edit").change(function(){
            imagePreviewEdit(this)
        })

        $('.image-popup').magnificPopup({
            type: 'image'
            // other options
        })
    })
</script>

<!-- Pusher -->
<script src="http://js.pusher.com/3.1/pusher.min.js"></script>
<script src="{{ url('js/app.js') }}"></script>
<script type="text/javascript">
    let pendingReseller = () => {
        var url = "{{ route('reseller.pending') }}"

        return fetch(url).then(response => response.json())
    }

    let pendingOrder = () => {
        var url = "{{ route('order.pending') }}"

        return fetch(url).then(response => response.json())
    }

    let pendingPayment = () => {
        var url = "{{ route('order_payment.pending') }}"

        return fetch(url).then(response => response.json())
    }

    const Echo = window.Echo
    
    @if(auth()->user()->isReseller())
    let verifiedResellerChannel = Echo.private('channel-reseller.{{ auth()->user()->id }}')
    verifiedResellerChannel.listen('ResellerEvent', function(data) {
        if(data.data.action == "hide_unverified_reseller") {
            $('#unverified-reseller').slideUp()
        } else {
            $('#unverified-reseller').slideDown()
        }
        toast(data.data.success, data.data.message, 5000)
        // console.log(data)
        table.draw()
    })
    @endif

    @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
    let verificationRequestChannel = Echo.private('channel-admin')
    verificationRequestChannel.listen('AdminEvent', function(data) {
        if(data.data.action = "update_pending_order_count") {
            pendingOrder().then(json => {
                if(json.data.count < 1) {
                    $('#pending_order_count').css('display', 'none')
                } else {
                    $('#pending_order_count').text(json.data.count)
                    $('#pending_order_count').css('display', 'block')
                }
            })
        }

        if(data.data.action = "update_pending_reseller_count") {
            pendingReseller().then(json => {
                if(json.data.count < 1) {
                    $('#pending_reseller_count').css('display', 'none')
                } else {
                    $('#pending_reseller_count').text(json.data.count)
                    $('#pending_reseller_count').css('display', 'block')
                }
            })
        }

        if(data.data.action = "update_pending_order_payment_count") {
            pendingPayment().then(json => {
                if(json.data.count < 1) {
                    $('#pending_order_payment_count').css('display', 'none')
                } else {
                    $('#pending_order_payment_count').text(json.data.count)
                    $('#pending_order_payment_count').css('display', 'block')
                }
            })
        }

        toast(data.data.success, data.data.message, 5000)
        table.draw()
    })
    @endif
</script>
@yield('js')