import {intervalToDuration, parseISO, isPast} from 'date-fns';

const clock = document.querySelector('#clock');
const countdown = document.querySelector('#countdown');
const celebration = document.querySelector('#celebration');

if (clock && countdown) {
  const yearsEl = document.querySelector('#years');
  const monthsEl = document.querySelector('#months');
  const daysEl = document.querySelector('#days');
  const hoursEl = document.querySelector('#hours');
  const minutesEl = document.querySelector('#minutes');
  const secondsEl = document.querySelector('#secondes');

  const targetDate = parseISO(clock.dataset.datetime);
  let intervalId = null;

  const setValue = (element, value) => {
    if (element) {
      element.style.setProperty('--value', value);
    }
  };

  const showCelebration = () => {
    countdown.classList.add('hidden');
    celebration.classList.remove('hidden');
    if (intervalId) {
      clearInterval(intervalId);
    }
  };

  const updateCountdown = () => {
    const now = new Date();

    if (isPast(targetDate)) {
      showCelebration();
      return;
    }

    const duration = intervalToDuration({
      start: now,
      end: targetDate
    });

    setValue(yearsEl, duration.years || 0);
    setValue(monthsEl, duration.months || 0);
    setValue(daysEl, duration.days || 0);
    setValue(hoursEl, duration.hours || 0);
    setValue(minutesEl, duration.minutes || 0);
    setValue(secondsEl, duration.seconds || 0);
  };

  updateCountdown();
  intervalId = setInterval(updateCountdown, 1000);

  setTimeout(() => {
    if (!isPast(targetDate)) {
      countdown.classList.remove('invisible');
    }
  }, 100);
}