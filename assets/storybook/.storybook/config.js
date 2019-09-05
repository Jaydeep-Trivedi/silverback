// Add the web components polyfill to all stories.
import '@babel/polyfill';
import '@webcomponents/webcomponentsjs/webcomponents-bundle';

import { addDecorator, configure } from '@storybook/html';
import { withA11y } from '@storybook/addon-a11y';

import Twig from 'twig';
import addDrupalFilters from 'twig-drupal-filters';

// Import styles
import '../styles.css';

// Add the filters to Drupal.
addDrupalFilters(Twig);

// Automatically import all files ending in *.stories.js
const twig = require.context('../twig', true, /\.stories\.(ts|js)$/);
const editor = require.context('../editor', true, /\.stories\.(ts|js)$/);
function loadStories() {
  twig.keys().forEach(filename => twig(filename));
  editor.keys().forEach(filename => editor(filename));
}

// Helps make UI components more accessible.
addDecorator(withA11y);

configure(loadStories, module);
