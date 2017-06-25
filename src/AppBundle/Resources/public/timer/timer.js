import $ from "jquery";
import moment from "moment";
import precise from "moment-precise-range";
const preciseDiff = precise(moment);

export default () => {
  const $clock = $('#clock');
  if ($clock.length) {

    const timestamp = parseInt($clock.data('timestamp'), 10) * 1000;
    const deadline = new Date(timestamp);
    initializeClock($clock, deadline);
  }
}

function getTimeRemaining(endTime) {
  console.log(preciseDiff(endTime, new Date(), {returnObject: true}));
  return preciseDiff(endTime, new Date(), {returnObject: true});
}

function initializeClock($clock, endTime) {
  const $years = $clock.find('.years');
  const $months = $clock.find('.months');
  const $days = $clock.find('.days');
  const $hours = $clock.find('.hours');
  const $minutes = $clock.find('.minutes');
  const $secondes = $clock.find('.seconds');

  function updateClock() {
    const t = getTimeRemaining(endTime);

    $years.text(t.years);
    $months.text(t.months);
    $days.text(t.days);
    $hours.text(('0' + t.hours).slice(-2));
    $minutes.text(('0' + t.minutes).slice(-2));
    $secondes.text(('0' + t.seconds).slice(-2));

    if (t.total <= 0) {
      clearInterval(timeInterval);
    }
  }

  updateClock();
  const timeInterval = setInterval(updateClock, 1000);
}
