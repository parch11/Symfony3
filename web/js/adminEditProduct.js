// permet d'afficher un aperçu de l'image selectionné
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#imagepreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#appbundle_product_imageFile_file").change(function () {
    readURL(this);
});

// met en forme la suppression de l'image
$("#appbundle_product_imageFile_delete").after($(".vich-file > div > label"));
$(".vich-file > div > label").html("Cocher pour supprimer l'image")
$(".vich-file > div").addClass("form-check")
$(".vich-file > div > label").addClass("form-check-label")
$("#appbundle_product_imageFile_delete").addClass("form-check-input")

// met en forme le téléchargement de l'image
$(".vich-file > a").addClass("btn btn-info")
$(".vich-file > a").html("Télécharger l'image")
