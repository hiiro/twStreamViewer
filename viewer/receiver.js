$(document).ready(function() {

  var capacity  = 100;
  var icon_size = 48;
  var pusher_key = '3c575869b230aaec3e20';
  var pusher_channel_name = 'stream';
  var pusher_event_name = 'sv';

  function format(text) {
    return text;
  }

  function cutoff() {
    if ($("#stream div").size() >= capacity) {
      $("#stream div:last").slideDown(100, function() {
        $(this).remove();
      });
    }
  }

  function prepend(element) {
    element.hide().prependTo($("#stream")).slideDown("fast");
    cutoff();
  }

  var stream = new Pusher(pusher_key, pusher_channel_name);

  stream.bind(pusher_event_name, function(message) {
    var data = message.data;
    var user = data.user;

    if (user) {
//      var id                = data.id;
      var id_str            = data.id_str;
      var text              = data.text;
      var screen_name       = user.screen_name;
      var name              = user.name
      var profile_image_url = user.profile_image_url;

      var div = $("<div/>")
                .addClass("tweet")
                .append($("<p/>")
                        .append($("<img/>")
                                .addClass("icon")
                                .attr({ src: profile_image_url, alt: screen_name, width: icon_size, height: icon_size }))
                        .append($("<span/>")
                                .addClass("screen_name")
                                .append($("<a/>")
                                        .attr({ href: "http://twitter.com/#!/" + screen_name + "/status/" + id_str, target: "_blank" })
                                        .text(name + "(" + screen_name + ")")))
                        .append(format(text)));

      prepend(div);
    }
  });
});
