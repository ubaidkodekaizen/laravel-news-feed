import { Picker } from 'https://esm.sh/emoji-picker-element@1.18.2';

const picker = new Picker({ locale: 'en' });
picker.style.position = 'absolute';
picker.style.zIndex = '9999';
picker.style.display = 'none';
document.body.appendChild(picker);

const emojiBtn = document.getElementById('emojiBtn');
const textArea = document.getElementById('postText');
emojiBtn?.addEventListener('click', (event) => {
    const rect = emojiBtn.getBoundingClientRect();
    picker.style.top = `${rect.bottom + window.scrollY}px`;
    picker.style.left = `${rect.left + window.scrollX}px`;
    picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
});
picker.addEventListener('emoji-click', event => {
    textArea.value += event.detail.unicode;
    picker.style.display = 'none';
});

const modal = document.getElementById('postModal');
modal?.addEventListener('hidden.bs.modal', () => {
    picker.style.display = 'none';
});
