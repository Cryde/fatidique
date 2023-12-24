import {intervalToDuration, parseISO} from 'date-fns';

const clock = document.querySelector('#clock');
const countdown = document.querySelector('#countdown');

if (clock && countdown) {
  const yearsContainer = document.querySelector('#years');
  const monthsContainer = document.querySelector('#months');
  const daysContainer = document.querySelector('#days');
  const hoursContainer = document.querySelector('#hours');
  const minutesContainer = document.querySelector('#minutes');
  const secondsContainer = document.querySelector('#secondes');

  const changeValues = () => {
    const values = calculateInterval();
    yearsContainer.style.setProperty('--value', (values.years || 0).toString());
    monthsContainer.style.setProperty('--value', (values.months || 0).toString());
    daysContainer.style.setProperty('--value', (values.days || 0).toString());
    if (hoursContainer) {
      hoursContainer.style.setProperty('--value', (values.hours || 0).toString());
    }
    if (minutesContainer) {
      minutesContainer.style.setProperty('--value', (values.minutes || 0).toString());
    }
    if (secondsContainer) {
      secondsContainer.style.setProperty('--value', (values.seconds || 0).toString());
    }
  };
  changeValues();
  setInterval(changeValues, 1000);

  setTimeout(() => {
    countdown.classList.remove('invisible');
  }, 100)
}


function calculateInterval() {
  return intervalToDuration({
    start: new Date(),
    end: parseISO(clock.dataset.datetime)
  });
}