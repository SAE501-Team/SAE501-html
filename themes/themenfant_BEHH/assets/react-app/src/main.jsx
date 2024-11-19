import React from "react";
import ReactDOM from "react-dom/client";
import App from "./App";
import "./index.css";
import Test2 from "./component/Test2";

const rootElement = document.getElementById("react-root");

  ReactDOM.createRoot(rootElement).render(
    <React.StrictMode>
      <App />
    </React.StrictMode>
  );
