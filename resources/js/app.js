import './bootstrap';
import './my-custom';
// Initialization for ES Users
import {
  Dropdown,
  Ripple,
  initTWE,
} from "tw-elements";

initTWE({ Dropdown, Ripple });

import 'flowbite';

// AOS ..
import AOS from 'aos';
import 'aos/dist/aos.css'; // You can also use <link> for styles
// ..
AOS.init();