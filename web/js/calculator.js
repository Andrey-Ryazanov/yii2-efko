$("#calc-button").on("click", function(e) {
    e.preventDefault();

    let data = $("#calc-button").closest("form").serialize();
    let url = $("#calc-button").closest("form").attr("action");
    let method = $("#calc-button").closest("form").attr("method");

    $.ajax({
      url: url,
      type: method,
      data: data,
      success: function(response) {
          $("div[data-widget=result-table]").html(response);
      },
  });

  return false;
});