import $ from "jquery";

export default () => {
  const $clock = $('#clock');
  if ($clock.length) {

    const deadline = new Date(Date.parse(new Date()) + 15 * 24 * 60 * 60 * 1000);
    initializeClock($clock, deadline);
  }
}

function getTimeRemaining(endTime) {
  const t = Date.parse(endTime) - Date.parse(new Date());
  const seconds = Math.floor((t / 1000) % 60);
  const minutes = Math.floor((t / 1000 / 60) % 60);
  const hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  const days = Math.floor(t / (1000 * 60 * 60 * 24));

  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeClock($clock, endTime) {
  const daysSpan = $clock.find('.days');
  const hoursSpan = $clock.find('.hours');
  const minutesSpan = $clock.find('.minutes');
  const secondsSpan = $clock.find('.seconds');

  function updateClock() {
    const t = getTimeRemaining(endTime);

    daysSpan.text(t.days);
    hoursSpan.text(('0' + t.hours).slice(-2));
    minutesSpan.text(('0' + t.minutes).slice(-2));
    secondsSpan.text(('0' + t.seconds).slice(-2));

    if (t.total <= 0) {
      clearInterval(timeInterval);
    }
  }

  updateClock();
  const timeInterval = setInterval(updateClock, 1000);
}
