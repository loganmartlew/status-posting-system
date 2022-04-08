const resetBtn = document.querySelector('.resetbtn');

const statusCodeInput = document.querySelector('#statuscode');
const statusInput = document.querySelector('#status');
const publicRadio = document.querySelector('#public');
const dateInput = document.querySelector('#date');
const likeBox = document.querySelector('#like');
const commentBox = document.querySelector('#comment');
const shareBox = document.querySelector('#share');

resetBtn.addEventListener('click', () => {
  statusCodeInput.value = '';
  statusInput.value = '';
  publicRadio.checked = true;
  dateInput.value = formatToIsoDate(new Date().toLocaleDateString());
  likeBox.checked = false;
  commentBox.checked = false;
  shareBox.checked = false;
});

function formatToIsoDate(localeDateString) {
  const dateArr = localeDateString.split('/');

  const extendedDateArr = dateArr.map(string => {
    if (string.length === 1) {
      return '0' + string;
    }

    return string;
  });

  const isoString = `${extendedDateArr[2]}-${extendedDateArr[0]}-${extendedDateArr[1]}`;

  return isoString;
}
