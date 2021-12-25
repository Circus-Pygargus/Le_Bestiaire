const monsterNavbar = function () {

    const monsterContentBtns = document.querySelectorAll('.monster-content-btn');
    const monsterContentDiv = document.querySelector('#monster-content');
    const monsterSubContentDivs = monsterContentDiv.querySelectorAll('.monster-content--sub-div');

    monsterContentBtns.forEach(btn => {
        if (!btn.classList.contains('disabled')) {
            btn.addEventListener('click', () => {
                monsterContentBtns.forEach(button => {
                    button.classList.remove('active');
                });
                btn.classList.add('active');

                monsterSubContentDivs.forEach(contentDiv => {
                    contentDiv.classList.add('hidden');
                });
                monsterContentDiv.querySelector('[data-content="' + btn.dataset.target + '"]').classList.remove('hidden');
            });
        }
    });
};

module.exports = monsterNavbar;
