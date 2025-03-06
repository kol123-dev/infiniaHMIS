import * as Turbo from "@hotwired/turbo";

if (!window.Turbo) {
    window.Turbo = Turbo;
    Turbo.start();
}

export default Turbo;
