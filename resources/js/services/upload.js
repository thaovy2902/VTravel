import { storage } from "./firebase";

export async function uploadImage(folder, image) {
  return new Promise((resolve, reject) => {
    const originalName = image.name;
    const ext = originalName.slice(originalName.lastIndexOf("."));
    const time = new Date().getTime();
    const imageName = time + ext;
    const uploadTask = storage.ref(`images/${folder}/${imageName}`).put(image);
    uploadTask.on(
      "state_changed",
      snapshot => {},
      error => {
        reject(error);
      },
      () => {
        // complete function ....
        storage
          .ref(`images/${folder}`)
          .child(imageName)
          .getDownloadURL()
          .then(url => {
            resolve(url);
          });
      }
    );
  });
}
