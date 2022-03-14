jQuery(function ($) {
  $("body").on("click", ".aw_upload_image_button", function (e) {
    console.log("asdfffffffffff");
    e.preventDefault();
    aw_uploader = wp
      .media({
        title: "Custom image",
        button: {
          text: "Use this image",
        },
        multiple: false,
      })
      .on("select", function () {
        var attachment = aw_uploader.state().get("selection").first().toJSON();
        $("#aw_custom_image").val(attachment.url);
      })
      .open();
  });
});

console.log("jjjjjjjjjj");
