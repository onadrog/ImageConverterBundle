interface ImageConverterModal {
  openModalInput: HTMLInputElement;
  modal: HTMLElement;
  closeBtn: HTMLElement;
  saveBtn: HTMLInputElement;
  cancelBtn: HTMLInputElement;
  radioInputs: NodeList;
  altInput: HTMLInputElement;
  entityInput: HTMLInputElement;
}

class ImageConverterModal extends HTMLElement {
  constructor() {
    super();
    this.openModalInput = this.querySelector("#imc-openModal");
    this.modal = this.querySelector("#imc-modal-fields");
    this.closeBtn = this.querySelector(".imc-close");
    this.saveBtn = this.querySelector(".imc-modal-save");
    this.cancelBtn = this.querySelector(".imc-modal-cancel");
    this.radioInputs = this.querySelectorAll("input[type='radio']");
    this.altInput = this.querySelector("input[data-name='alt']");
    this.entityInput = this.querySelector(".entity_value");
    this.init();
  }

  init() {
    this.openModalInput.addEventListener("click", () => {
      this.modal.removeAttribute("style");
      this.className += "imc-open";
      this.openModalInput.className += "imc-open";
      this.closeEvt();
      this.cancelEvt();
      this.radioEvnt();
    });
  }

  closeEvt = () => {
    [this.closeBtn, this.saveBtn].forEach((btn) => {
      btn.addEventListener("click", () => {
        this.modal.style.display = "none";
        this.openModalInput.classList.remove("imc-open");
        this.removeAttribute("class");
      });
    });
  };

  cancelEvt = () => {
    this.cancelBtn.addEventListener("click", () => {
      this.querySelectorAll("input").forEach((inputs) => {
        if (inputs.type === "text" || inputs.type === "file") {
          inputs.value = "";
        } else {
          inputs.checked = false;
        }
      });
      this.altInput.removeAttribute("disabled");
      this.entityInput.removeAttribute("value");
    });
  };

  radioEvnt = () => {
    this.radioInputs.forEach((radio) => {
      radio.addEventListener("change", (e) => {
        const target = <HTMLInputElement>e.target;
        this.altInput.disabled = true;
        this.altInput.value = target.dataset.alt;
        this.entityInput.value = target.value;
      });
    });
  };
}

customElements.define("image-converter-modal", ImageConverterModal);
