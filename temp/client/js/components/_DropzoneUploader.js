import { BASE_URL, fetchApi } from "../api";
import { LNG_ERROR } from "../util/consts";
import { toast } from "../util/util";

export function DropzoneUploader(options) {
  this.render = function () {
    let defaultOptions = {
      cls: "upload-file",
      type: "public", 
      formats: "image/jpg,image/jpeg",
    };
    options = { ...defaultOptions, ...options };
    var previewNode = document.querySelector("#template");
    previewNode.id = "";
    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);
    const cardCreview = document.querySelector(
      "." + options.cls + " #card-preview"
    );
    const startBtn = document.querySelector("." + options.cls + " .start");
    const cancelBtn = document.querySelector(
      "." + options.cls + " #actions .cancel"
    );
    const acceptFormats = options.formats;
    let id = "dropzone_preview";
    if (options.id) {
      id = options.id;
    }
    const clickable = "." + options.cls + " .fileinput-button";
    Dropzone.prototype.isFileExist = function (file) {
      var i;
      if (this.files.length > 0) {
        for (i = 0; i < this.files.length; i++) {
          if (
            this.files[i].name === file.name &&
            this.files[i].size === file.size &&
            this.files[i].lastModifiedDate.toString() ===
              file.lastModifiedDate.toString()
          ) {
            return true;
          }
        }
      }
      return false;
    };
    Dropzone.prototype.addFile = function (file) {
      file.upload = {
        progress: 0,
        total: file.size,
        bytesSent: 0,
      };
      if (this.options.preventDuplicates && this.isFileExist(file)) {
        toast("e", "فایل تکراری میباشد", LNG_ERROR);
        return;
      }
      this.files.push(file);
      file.status = Dropzone.ADDED;
      this.emit("addedfile", file);
      this._enqueueThumbnail(file);
      return this.accept(
        file,
        (function (_this) {
          return function (error) {
            if (error) {
              file.accepted = false;
              _this._errorProcessing([file], error);
            } else {
              file.accepted = true;
              if (_this.options.autoQueue) {
                _this.enqueueFile(file);
              }
            }
            return _this._updateMaxFilesReachedClass();
          };
        })(this)
      );
    };
    var myDropzone = new Dropzone("div#" + id, {
      url: BASE_URL + "file",
      method: "POST",
      thumbnailWidth: 80,
      maxFilesize: options.maxFilesize,
      thumbnailHeight: 80,
      createImageThumbnails: true,
      acceptedFiles: acceptFormats,
      autoQueue: false,
      maxFiles: 8,
      previewTemplate: previewTemplate,
      previewsContainer: "#" + id,
      clickable,
      dictDuplicateFile: "Duplicate Files Cannot Be Uploaded",
      preventDuplicates: true,
      success: function (file, response) {
        if (options.type === "product") {
          if (file.width == 700 && file.height == 500) {
            file.previewElement.querySelector(".selectable").classList.add("selectable--yes"); 
            if (document.querySelector('.'+options.cls+' [name="main-file"]:checked') == null) { 
              file.previewElement.querySelector('[name="main-file"]').setAttribute('checked', 'checked');  
            }

          }else{
            file.previewElement.querySelector('[name="main-file"]').setAttribute('disabled','disabled');
          } 
          file.previewElement.querySelector(".selectable").id = "selectable-" + response.data.id;
          file.previewElement.querySelector('[name="main-file"]').setAttribute("for", "#selectable-" + response.data.id);
          file.previewElement.querySelector('[name="main-file"]').value=response.data.id;
          file.previewElement.innerHTML +=
            '<input type="hidden" value="' +
            response.data.id +
            '"  data-id="' +
            response.data.id +
            '" data-dir="' +
            response.data.dir +
            '" class="files"/>';
        } else {
          file.previewElement.innerHTML +=
            '<input type="hidden" value="' +
            response.data.dir +
            '" data-id="' +
            response.data.id +
            '" data-dir="' +
            response.data.dir +
            '" class="files"/>';
        } 
      },
      uploadprogress(file, progress, bytesSent) {
        if (file.previewElement) {
          for (let node of file.previewElement.querySelectorAll(
            "[data-dz-uploadprogress]"
          )) {
            node.nodeName === "PROGRESS"
              ? (node.value = progress)
              : (node.style.width = `${progress}%`);
          }
        }
      },
      // Called whenever the total upload progress gets updated.
      // Called with totalUploadProgress (0-100), totalBytes and totalBytesSent
      totaluploadprogress(data) {
        console.log(data);
      },
      error: function (file, response) {
        if (file.previewElement) {
          file.previewElement.remove();
        }
        console.log("error");
        if (typeof response == "Object") {
          toast("e", response.data, LNG_ERROR);
        } else {
          toast("e", response, LNG_ERROR);
        }
        console.log(response);
      },
      reset() {
        startBtn.classList.add("d-none");
        cardCreview.classList.add("d-none");
        cancelBtn.classList.add("d-none");
      },
    });

    myDropzone.on("addedfile", function (file) {
      startBtn.classList.remove("d-none");
      cardCreview.classList.remove("d-none");
      file.previewElement.querySelector(".dz-type").innerHTML = file.type;
      for (let node of file.previewElement.querySelectorAll(
        "[data-dz-uploadprogress]"
      )) {
        node.nodeName === "PROGRESS"
          ? (node.value = progress)
          : (node.style.width = `0%`);
      }
      file.previewElement.querySelector(".start").onclick = function () {
        myDropzone.enqueueFile(file);
      };
    });

    myDropzone.on("sending", function (file, xhr, formData) {
      formData.append("dest", options.dest);
      formData.append("type", options.type);
      if (options.hasOwnProperty('onSend') &&   options.onSend instanceof Function) {
          formData.append("more", JSON.stringify(options.onSend()));
      }
      file.previewElement
        .querySelector(".start")
        .setAttribute("disabled", "disabled");
    });

    myDropzone.on("queuecomplete", function (progress) {
      startBtn.classList.add("d-none");
      cardCreview.classList.add("d-none");
      cancelBtn.classList.add("d-none");
      cancelBtn.classList.add("d-none");
      if (options.hasOwnProperty("onComplete")) {
        options.onComplete();
      }
    });

    startBtn.onclick = function (e) {
      myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
    };

    cancelBtn.onclick = function () {
      myDropzone.removeAllFiles(true);
    };
  };
}
