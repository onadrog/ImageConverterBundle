const input = <HTMLInputElement>(
    document.querySelector("input[data-name='image_converter']")
  ),
  altInput = <HTMLInputElement>document.querySelector("input[data-name='alt']"),
  reader = new FileReader(),
  formData = new FormData(),
  canvas = document.createElement("canvas"),
  ctx = canvas.getContext("2d"),
  image = new Image(),
  newcanvas = document.createElement("canvas"),
  newctx = newcanvas.getContext("2d"),
  newImg = new Image(),
  newinput = document.createElement("input");

input.addEventListener("change", (e) => {
  const form = document.querySelector("form");
  const innerForm = document.getElementById(form.name);
  const target = <HTMLInputElement>e.target;
  const file = target.files[0];
  const fileName = file.name;
  const quality = parseInt(input.dataset.qual, 10) / 100;
  image.height = 200;
  reader.onload = (e) => {
    image.src = <string>e.target.result;
  };
  image.onload = () => {
    canvas.width = image.width;
    canvas.height = image.height;
  };

  reader.readAsDataURL(file);
  target.parentElement.append(image);

  const url = URL.createObjectURL(file);
  newImg.src = url;
  newImg.onload = () => {
    console.log(newImg.height);
    URL.revokeObjectURL(url);
    newcanvas.width = newImg.width;
    newcanvas.height = newImg.height;
    newctx.drawImage(newImg, 0, 0);

    newinput.type = "file";
    newinput.name = `${form.name}[${input.dataset.prop}][original_file]`;
    newinput.style.display = "none";
    let container2 = new DataTransfer();
    container2.items.add(file);
    newinput.files = container2.files;
    innerForm.append(newinput);
    newcanvas.toBlob(
      (blob) => {
        const newfile = new File([blob], fileName, {
          type: "image/webp",
          lastModified: new Date().getTime(),
        });
        let container = new DataTransfer();
        container.items.add(newfile);
        input.files = container.files;
      },
      "image/webp",
      quality
    );
  };
});

const convertfile = (
  fileName: string,
  quality: number,
  input: HTMLInputElement,
  file: File
) => {
  const dataURI = newcanvas.toDataURL("image/webp", quality);
  const blob = new Blob([dataURI]);
  const newfile = new File([blob], fileName, {
    type: "image/webp",
    lastModified: new Date().getTime(),
  });
  console.log(newfile);
  let container = new DataTransfer();
  container.items.add(newfile);
  input.files = container.files;
};
