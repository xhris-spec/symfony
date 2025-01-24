/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

// import bootstrap
import * as bootstrap from "bootstrap";

// import fancybox
import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";

console.log(bootstrap);

Fancybox.bind("[data-fancybox]", {
  defaultType: "iframe",
  iframe: {
    css: {
      width: "800px",
      height: "600px",
    },
  },
});
