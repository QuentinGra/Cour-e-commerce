import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.querySelectorAll('ul.productImages li').forEach((productImage) => {
            this.addProductImageDeleteLink(productImage)
        })
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

        this.addProductImageDeleteLink(item);
    }

    addProductImageDeleteLink(item) {
        const removeFormButton = document.createElement('button');
        removeFormButton.innerText = 'Delete this image';
        removeFormButton.classList.add('btn');

        item.append(removeFormButton);

        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            // remove the li for the tag form
            item.remove();
        });
    }
}
