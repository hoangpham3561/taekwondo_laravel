<script>
    function fireDeleteConfirmPopup(form, title = "Are you sure?", text = "You will not be able to recover this imaginary file!", confirmText = "Yes, delete it!") {
        let e = Swal.mixin({
            buttonsStyling: !1,
            target: "#pages-container",
            customClass: {
                confirmButton: "btn btn-success m-1",
                cancelButton: "btn btn-danger m-1",
                input: "form-control"
            }
        });

        e.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: !0,
            customClass: {
                confirmButton: "btn btn-danger m-1",
                cancelButton: "btn btn-secondary m-1"
            },
            confirmButtonText: confirmText,
            html: !1,
            preConfirm: e => new Promise((e => {
                setTimeout((() => {
                    e()
                }), 50)
            }))
        }).then((t => {
            if (t.value) {
                // e.fire("Deleted!", "Your imaginary file has been deleted.", "success")
                console.log('ok');
                document.forms[form].submit();
            } else {
                //  "cancel" === t.dismiss && e.fire("Cancelled", "Your imaginary file is safe :)", "error")
            }
        }))
    }
</script>
