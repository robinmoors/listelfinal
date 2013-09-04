/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

EQ = {
    errormsg: "",
    messages: "",
    pages: "",
    CPU: "",
    OVR: "",
    ajaxProcessor: {
        process: [],
        requests: [],
        responses: [],
        feedbacks: [],
        pid: 0,
        curProcess: "",
        makeProcess: function(o) {
            if (o.name && o.process) {
                if (this.process[o.name] != o.process)
                    this.process[o.name] = o.process;
                this.requests[o.name] = [];
                this.responses[o.name] = [];
                this.feedbacks[o.name] = [];
            }
        },
        startProcess: function(pname) {
            if (this.curProcess == "")
                this.sendRequests(pname);
        },
        newRequest: function(o) {
            this.requests[o.process][this.pid] = new EQ.ajaxProcess({
                url: o.url,
                parameters: o.parameters,
                pid: this.pid,
                process: o.process,
                feedback: o.feedback
            });
            this.pid++;
        },
        sendRequests: function(pname) {
            var r = this.requests[pname];
            var i;
            for (i in r) {
                if (r[i].state == "pauzed") {
                    r[i].start();
                    //console.log("starting procesrequest for "+pname+" id::"+i);
                }
            }
        },
        saveResponse: function(response, pid, process) {
            this.responses[process][pid] = response;
            this.doAction(response, this.feedbacks[process][pid], process);
            //controleer of alle requests van het process zijn afgelopen en uitgevoerd, dan naar volgende proces springen..
        },
        saveFeedback: function(feedback, pid, process) {
            this.feedbacks[process][pid] = feedback;
        },
        getResponse: function(pid, process) {
            return this.responses[process][pid];
        },
        doAction: function(response, feedback, pname) {
            if (this.process[pname](response, feedback))
                return true;
            else
                return false;
        }
    },
    ajaxProcess: function(o) {
        this.GetXmlHttpObject = function() {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                return new XMLHttpRequest();
            }
            if (window.ActiveXObject) {
                // code for IE6, IE5
                return new ActiveXObject("Microsoft.XMLHTTP");
            }
            return null;
        };
        this.secured = o.secured || false;
        this.parameters = o.parameters || false;
        this.feedback = o.feedback || "none";
        this.url = o.url || false;
        this.pid = o.pid || 0;
        this.process = o.process || "";
        this.state = "pauzed";
        this.ready = false;
        this.xhr = this.GetXmlHttpObject();
        this.check = function() {
            if (this.xhr == null) {
                alert("Jouw browser ondersteund geen ajaxrequests, download mozilla firefox op http://getfirefox.com om veiliger en sneller te surfen!");
                return false;
            } else
                return true;
        };
        this.start = function() {
            if (this.check()) {
                this.state = "started";
                this.xhr.open("POST", this.url, true);
                //objectvariabellen in het xmlhttprequest object steken (binnen onreadystate zijn we die kwijt!)
                this.xhr.pid = this.pid;
                this.xhr.process = this.process;
                this.xhr.feedback = this.feedback;
                this.xhr.po = this;
                this.xhr.onreadystatechange = function() {
                    this.po.state = this.readyState;
                    if (this.readyState == 4) {
                        if (this.status == 200) {
                            this.po.state = "finished";
                            this.po.ready = true;
                            EQ.CPU.saveFeedback(this.feedback, this.pid, this.process);
                            EQ.CPU.saveResponse(this.responseText, this.pid, this.process);
                        }
                        else {
                            this.po.state = this.xhr.status;
                            this.po.ready = true;
                        }
                    }
                }
                this.xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //this.xhr.setRequestHeader("Content-length", this.parameters.length);
                //this.xhr.setRequestHeader("Connection", "close");
                if (this.parameters) {
                    var extra = "";
                    if (localStorage && localStorage.getItem("uid")) {
                        extra = "&uid=" + localStorage.getItem("uid") + "&pin=" + localStorage.getItem("pin");
                    }
                    this.xhr.send(this.parameters + extra);
                }
                else
                    this.xhr.send();
            } else {
                this.state = "ofail";
                this.ready = true;
            }
        }
    },
    change: function(div) {
        for (var i = 0; i < this.pages.length; i++) {
            $("#" + this.pages[i]).hide();
            $("#" + this.pages[i] + "-but").removeClass("selected");
        }
        $("#" + div + "-but").addClass("selected");
        $("#" + div).fadeIn(200);
    },
    formCheck: function(elem, l, ml, special, pref, obj) {
        var prefix = pref || "";
        var b = true;
        var elemn;
        if (elem === "" && obj) {
            elem = obj.value;
            elemn = obj.name;
        } else {
            elemn = elem.name;
        }
        if (elemn !== "submit" && !obj)
            elem = elem.value.toString();
        else if (!obj)
            elem = "";
        this.errormsg = "buzy";
        if (special)
            switch (special) {
                case "email":
                    if (!elem.match(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/)) {
                        this.errormsg = "form-match-email";
                        b = false;
                    }
                    break;
                case "telephone":
                    //011/48.01.88', '0497/150.583
                    if (!elem.match(/^(0)([4][0-9]{2}|[1-35-9][0-9])[/]((\d\d\d[.]\d\d\d)|(\d\d[.]\d\d[.]\d\d))$/)) {
                        this.errormsg = "form-match-telephone";
                        b = false;
                    }
                    break;
                case "postal":
                    if (!elem.match(/^[1-9]\d{3}$/)) {
                        this.errormsg = "form-match-postal";
                        b = false;
                    }
                    break;
                case "date":
                    if (!elem.match(/^([1-9]|0[1-9]|[12][0-9]|3[01])([/])([1-9]|0[1-9]|1[0-2])\2(19|20)\d\d$/)) {
                        this.errormsg = "form-match-date";
                        b = false;
                    }
            }
        if (l != "x") {
            if (elem.length < l) {
                this.errormsg = "form-length-short";
                b = false;
            }
        }
        if (ml != "x") {
            if (elem.length > ml) {
                this.errormsg = "form-length-max";
                b = false;
            }
        }
        if (!b) {
            $("#" + pref + "submit").html(EQ.messages['form-uncomplete']).addClass('wrong');
            $("#" + pref + elemn).html(EQ.messages[this.errormsg]).addClass('wrong');
        } else if (elemn != "submit") {
            this.errormsg = "form-complete";
            $("#" + pref + elemn).html(EQ.messages[this.errormsg]).removeClass('wrong').addClass('succes');
        }
        return b;
    },
    radioValue: function(radio) {
        var size = radio.length;
        for (i = 0; i < size; i++) {
            if (radio[i].checked)
                return radio[i].value;
        }
        ;
        return null;
    },
    getPopup: function(what, who) {
        var pars;
        var id = who;
        switch (what) {
            case "doktors":
                pars = {
                    pname: "doctorfetch",
                    url: "docs",
                    title: "Zorgverleners",
                    type: "zorgverlener",
                    action: "Bezig met ophalen van zorgverleners",
                    what: what
                };
                break;
            case "mantelzorgers":
                pars = {
                    pname: "mantelfetch",
                    url: "mantel",
                    title: "Mantelzorgers",
                    type: "mantelzorger",
                    action: "Bezig met ophalen van mantelzorgers",
                    what: what
                };
                break;
        }
        EQ.OVR = new EQ.overflow({
            title: pars.title
        });
        EQ.OVR.content = pars.action + '...<br/><img src=\'/listel_new/lib/images/flat-loader.gif\' />';
        EQ.OVR.refresh('c').open();

        EQ.CPU.makeProcess({
            name: pars.pname,
            process: function(resp) {
                var options = function(jsonvar) {
                    var i;
                    var html = "<form name='dokters'><select name='dokters' onchange='EQ.setPopup(\""+pars.what+"\",\""+id+"\",document.dokters.dokters.value)'>\n\
                                    <option value='0'>Kies een "+pars.type+"</option>";
                    for (i = 0; i < jsonvar.length; i++)
                    {
                        html += "<option value='" + jsonvar[i].id + "'>" + jsonvar[i].naam + "</option>";
                    }
                    return html + "</select></form>";
                };
                var json = EQ.jsp(resp);
                if (json.error) {
                    EQ.OVR.content = json.error;
                    EQ.OVR.refresh("c");
                } else if (json.succes) {
                    if (json.succes === 'positive') {
                        EQ.OVR.content = 'Kies een ' + pars.type + ':<br/>' + options(json.doks);
                        EQ.OVR.refresh('c');
                        EQ.login(json);
                    }
                    else {
                        EQ.OVR.content = '';
                        EQ.OVR.refresh('c');
                    }
                }
                //laad ook userobjecten in..
            }
        });
        var uid = EQ.getUid();
        EQ.CPU.newRequest({
            process: pars.pname,
            url: '/listelfinal/ecareplan/overleg/bewerkvoeg' + pars.url + '/' + id + '/' + uid,
            parameters: ''
        });
        EQ.CPU.startProcess(pars.pname);

    },
    getUid: function() {
        if (localStorage) {
            return localStorage.getItem("uid");
        } else
            return "";
    },
    iconAction: function(selector,icons,parameters){
            //selector = jquery css selector
           //icons:{bussy:"html",on:"html",off:"html",error:"html}
           //parameters:{pname:"procesnaam",url:"overleg/lkjl/lkjl",onvalue="0",offvalue="1"}
        var iconobj = icons;
        var pars = parameters;
        var css = selector;
        $(css).html("<img src='"+iconobj.bussy+"'/>");
        EQ.CPU.makeProcess({
            name: pars.pname,
            process: function(resp) {
                var json = EQ.jsp(resp);
                if (json.error) {
                    $(css).html("<img src='"+iconobj.error+"'/ title='"+json.error+"'>");
                } else if (json.succes) {
                    if (json.succes === 'positive') {
                        if(json.value == pars.onvalue) $(css).html("<img src='"+iconobj.on+"'/>");
                        if(json.value == pars.offvalue) $(css).html("<img src='"+iconobj.off+"'/>");
                        EQ.login(json);
                    }
                }
            }
        });
        var uid = EQ.getUid();
        EQ.CPU.newRequest({
            process: pars.pname,
            url: '/listelfinal/ecareplan/'+pars.url + '/' + uid,
            parameters: ''
        });
        EQ.CPU.startProcess(pars.pname);
    },
    jsp: function(j) {
        return jQuery.parseJSON(j);
    },
    login: function(json) {
        if (json.uid && json.pin) {
            if (localStorage) {
                localStorage.setItem("uid", json.uid);
                localStorage.setItem("pin", json.pin);
                return true;
            } else
                alert("Geen ondersteuning voor localstorage. U kan niet aanmelden!");
            return false;
        } else
            return false;
    },
    overflow: function(o) {
        this.title = o.t || o.title || "";
        this.sub = o.s || o.sub || "";
        this.head = o.head || o.h || "<hgroup><h3>" + this.title + "</h3><h7>" + this.sub + "</h7></hgroup>";
        this.content = o.c || o.content || "";
        this.footer = o.footer || o.f || "";
        this.button = o.button || "<a class='RoundedButton2' tabIndex='2' onclick='EQ.OVR.close();'><img src='/listelfinal/lib/images/Close.png' title='sluiten' alt=''/></a>";
        this.attached = false;
        this.clickevent = function() {
            EQ.OVR.close();
        }
        this.attach = function(o) {
            this.attached = o;
            this.head = this.attached.h;
            this.content = this.attached.c;
            return this;
        };
        this.center = function(Xwidth, Yheight, divid, maxout) {
            //scroll berekenen
            var scrolledX, scrolledY;
            if (self.pageYOffset) {
                scrolledX = self.pageXOffset;
                scrolledY = self.pageYOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
                scrolledX = document.documentElement.scrollLeft;
                scrolledY = document.documentElement.scrollTop;
            } else if (document.body) {
                scrolledX = document.body.scrollLeft;
                scrolledY = document.body.scrollTop;
            }
            //centre berekenen
            var centerX, centerY;
            if (self.innerHeight) {
                centerX = self.innerWidth;
                centerY = self.innerHeight;
            } else if (document.documentElement && document.documentElement.clientHeight) {
                centerX = document.documentElement.clientWidth;
                centerY = document.documentElement.clientHeight;
            } else if (document.body) {
                centerX = document.body.clientWidth;
                centerY = document.body.clientHeight;
            }
            //setup maxWidth and maxHeight
            if (maxout) {
                var b = Xwidth >= centerX || Yheight >= 600 ? true : false;
                Xwidth = Xwidth >= centerX ? (centerX - 10) : Xwidth;
                Yheight = Yheight >= (centerY * 0.9) ? (centerY * 0.9) : Yheight;
            }
            // Xwidth is the width of the div, Yheight is the height of the
            // div passed as arguments to the function:
            var leftOffset = scrolledX + ((centerX - Xwidth) / 2);
            var topOffset = scrolledY + ((centerY - Yheight) / 2);
            // The initial width and height of the div can be set in the
            // style sheet with display:none; divid is passed as an argument to // the function
            var o = document.getElementById(divid);
            var r = o.style;
            r.top = topOffset + 'px';
            r.left = leftOffset + 'px';

            if (maxout) {
                r.widht = Xwidth + 'px';
                r.height = Yheight + 'px';
                if (b)
                    r.overflow = "auto";
            }
            if (Yheight > centerY) {
                r.height = centerY + "px";
                r.top = scrolledY + "px";
                r.overflow = "auto";
            }
            if (Xwidth > centerX) {
                r.widht = centerX + "px";
                r.overflow = "auto";
            }
            return this;
        };
        this.open = function() {
            document.body.style.overflow = "hidden";
            $("#overflowbox").show();
            $("#overflow").on("click", EQ.OVR.clickevent);
            $("#vrflheader").html(this.head);
            $("#vrflcontent").html(this.content);
            $("#overflowcontent footer").html(this.footer + this.button);
            //$("#overflowcontent").height(document.documentElement.clientHeight+"px").width(document.documentElement.clientWidth+"px");
            this.center(document.documentElement.clientWidth, document.documentElement.clientHeight, "overflowcontent");
            $("#overflowcontent").show();
            return this;
        };
        this.close = function() {
            document.body.style.overflow = "visible";
            $("#oveflowcontent").hide();
            $("#overflowbox").hide();
            $("#overflow").off("click", EQ.OVR.clickevent);
            this.attached = false;
            return this;
        };
        this.refresh = function(a) {
            switch (a) {
                case "head":
                case "h":
                case"header":
                    if (this.attached)
                        this.head = this.attached.h;
                    else
                        this.head = "<hgroup><h3>" + this.title + "</h3><h7>" + this.sub + "</h7></hgroup>";
                    $("#vrflheader").html(this.head);
                    break;
                default:
                case "content":
                case "c":
                    if (this.attached)
                        this.content = this.attached.c;
                    $("#vrflcontent").html(this.content);
                    break;
                case "footer":
                case "f":
                    $("#overflowcontent footer").html(this.footer + this.button);
                    break;
            }
            return this;
        };
    },
    reRoute: function(url, gofast, extra) {
        var xtra = extra || "";
        switch (url) {
            case "home":
                url = "/listelfinal/ecareplan/home/welkom/";
                break;
            case "login":
                url = "/listelfinal/ecareplan/login/login/";
                break;
            case "eid":
                url = "/listelfinal/ecareplan/login/eid/";
                break;

            case "omb":
                url = "/listelfinal/ecareplan/omb/";
                break;
            case "ombbewerk":
                url = "/listelfinal/ecareplan/omb/bewerk/";
                break;
            case "ombnieuw":
                url = "/listelfinal/ecareplan/omb/nieuw/";
                break;

            case "overleg":
                url = "/listelfinal/ecareplan/overleg/toon/";
                break;
            case "overlegbewerk":
                url = "/listelfinal/ecareplan/overleg/bewerk/";
                break;
            case "overlegnieuw":
                url = "/listelfinal/ecareplan/overleg/nieuw/";
                break;
            case "refresh":
            default :
                var bool = true;
                window.refresh();
                break;
        }
        if (!bool) {
            url += xtra + '/' + EQ.getUid();
            var time = gofast === true ? 0 : 10000;
            setTimeout(function() {
                window.location = url;
            }, time);
        }
    },
    setPopup: function(what, who, wiht) {
        if(wiht===0) return false;
        var pars;
        var id = who;
        switch (what) {
            case "doktors":
                pars = {
                    pname: "doctoradd",
                    url: "docs",
                    title: "Zorgverleners",
                    type: "zorgverlener",
                    action: "De zorgverlener wordt toegevoegd aan het overleg...",
                    finish: "toegevoegd",
                    pars: wiht
                };
                break;
            case "mantelzorgers":
                pars = {
                    pname: "manteladd",
                    url: "mantel",
                    title: "Mantelzorgers",
                    type: "mantelzorger",
                    action: "De mantelzorger wordt toegevoegd aan het overleg...",
                    finish: "toegevoegd",
                    pars: wiht
                };
                break;
           case "verwijder": default:
               pars = {
                   pname: "deletefetch",
                   url:"verw",
                   title:"teamlid verwijderen",
                   type: "teamlid",
                   action: "Bezig met het verwijderen van een teamlid",
                   finish: "verwijderd",
                   pars: wiht
               }; break;
        }
        EQ.OVR = new EQ.overflow({
            title: pars.title
        });
        EQ.OVR.content = pars.action + '...<br/><img src=\'/listel_new/lib/images/flat-loader.gif\' />';
        EQ.OVR.refresh('c').open();

        EQ.CPU.makeProcess({
            name: pars.pname,
            process: function(resp) {
                var json = EQ.jsp(resp);
                if (json.error) {
                    EQ.OVR.content = json.error;
                    EQ.OVR.refresh("c");
                } else if (json.succes) {
                    if (json.succes === 'positive') {
                        EQ.OVR.content = 'De/het '+pars.type+' werd '+pars.finish+'.<br/>Het overleg wordt ge&uuml;dated...';
                        EQ.OVR.refresh('c');
                        EQ.login(json);
                        document.location.reload(true);
                    }
                    else {
                        EQ.OVR.content = '';
                        EQ.OVR.refresh('c');
                    }
                }
                //laad ook userobjecten in..
            }
        });
        var uid = EQ.getUid();
        EQ.CPU.newRequest({
            process: pars.pname,
            url: '/listelfinal/ecareplan/overleg/bewerkinvoegen' + pars.url + '/' + id + '/' + uid,
            parameters: "pars="+pars.pars
        });
        EQ.CPU.startProcess(pars.pname);
    }
};