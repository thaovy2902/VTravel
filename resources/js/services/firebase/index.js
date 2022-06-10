import firebase from "firebase/app";
import "firebase/storage";

// Initialize Firebase
var firebaseConfig = {
  apiKey: "AIzaSyAp4bkPzIT_PgxURfR2aIGngM8ArIXP35E",
  authDomain: "graduation-4e427.firebaseapp.com",
  databaseURL: "https://graduation-4e427.firebaseio.com",
  projectId: "graduation-4e427",
  storageBucket: "graduation-4e427.appspot.com",
  messagingSenderId: "266525495476",
  appId: "1:266525495476:web:9cee6a5e3a4c69913947e1",
  measurementId: "G-4X4ZT1R4C9"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const storage = firebase.storage();

export { storage, firebase as default };
