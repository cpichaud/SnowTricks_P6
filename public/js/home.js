const cardItems = document.querySelectorAll('.card-item');
const loadMoreBtn = document.getElementById('load-more');
const cardCount = document.getElementById('card-count');
const cardTotal = document.getElementById('card-total');
const batchSize = 4;

let currentBatch = batchSize;

function showCards(start, end) {
  for (let i = start; i < end; i++) {
    cardItems[i].classList.remove('hide');
  }
}

function hideCards(start, end) {
  for (let i = start; i < end; i++) {
    cardItems[i].classList.add('hide');
  }
}

function updateCardCount() {
  const visibleCount = document.querySelectorAll('.card-item:not(.hide)').length;
  cardCount.innerText = visibleCount;
  cardTotal.innerText = cardItems.length;
}

function onLoadMore() {
  const start = currentBatch;
  const end = currentBatch + batchSize;
  showCards(start, end);
  currentBatch += batchSize;
  updateCardCount();
  if (currentBatch >= cardItems.length) {
    loadMoreBtn.classList.add('hide');
  }
}

hideCards(currentBatch, cardItems.length);
showCards(0, currentBatch);
updateCardCount();
loadMoreBtn.addEventListener('click', onLoadMore);
