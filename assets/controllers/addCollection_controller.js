import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        const btn = this.element.querySelector('.add_item_link');

        btn.addEventListener('click', this.addFormToCollection.bind(this));
    }

    addFormToCollection(e) {
        const collectionHolder = this.element.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('li');

        item.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );

        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;
    };
}
