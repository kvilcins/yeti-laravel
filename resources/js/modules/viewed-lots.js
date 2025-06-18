document.addEventListener('DOMContentLoaded', () => {
    const lotIdMetaTag = document.querySelector('meta[name="lot-id"]');
    if (lotIdMetaTag) {
        const lotId = parseInt(lotIdMetaTag.getAttribute('content'));
        if (!isNaN(lotId)) addViewedLot(lotId);
    }
    updateViewedLots();
});

const addViewedLot = lotId => {
    const viewedLots = JSON.parse(localStorage.getItem('viewed_lots')) || [];
    if (!viewedLots.includes(lotId)) {
        viewedLots.push(lotId);
        localStorage.setItem('viewed_lots', JSON.stringify(viewedLots));
    }
};

const updateViewedLots = () => {
    const viewedLots = JSON.parse(localStorage.getItem('viewed_lots')) || [];
    document.cookie = `viewed_lots=${JSON.stringify(viewedLots)}; path=/`;
};
