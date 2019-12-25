import $ from "jquery";
import "tempusdominus-bootstrap-4";
import 'moment/locale/fr';

export default () => {
  $('.js-datetime-picker').datetimepicker({
    icons: {
      time: "fa fa-clock-o",
      date: "fa fa-calendar",
      up: "fa fa-arrow-up",
      down: "fa fa-arrow-down",

      previous: 'fa fa-chevron-left',
      next: 'fa fa-chevron-right',
      today: 'fa fa-screenshot',
      clear: 'fa fa-trash',
      close: 'fa fa-remove'
    },
    useStrict: true,
    sideBySide: true,
    locale: 'fr'
  });
}