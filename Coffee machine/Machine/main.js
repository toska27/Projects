
let machine = {
    water: 400,
    coffee: 200,
    milk: 100,
    credit: 100,
    waterStatus: function(){
        document.getElementById('water-status').innerText = this.water;
    },
    addWater: function(){
        if(this.water >= 400){
            alert('The machine is already filled with water');
        } else{
            let fill = prompt('Add water:');
            if(fill === null){
                return;
            }
            if(isNaN(fill) || fill<0 || fill%1!==0){
                alert('Enter a positive integer');
                return;
            } else{
                let newWater = this.water + Number(fill);
                if(newWater>400){
                    this.water = 400;
                    machine.waterStatus();
                } else{
                    this.water = newWater;
                    machine.waterStatus();
                }
            }         
        }
        message('You added water');
    },
    emptyWater: function(number){
        if(number<=this.water){
            this.water -= number;
            machine.waterStatus();
            message('Pouring water');
        } else{
            message('Machine is out of water');
            return;
        }
    },  
    coffeStatus: function(){
        document.getElementById('coffee-status').innerText = this.coffee;
    },
    addCoffee: function(){
        if(this.coffee >= 200){
            alert('The machine is already filled with coffee');
        } else{
            let fill = prompt('Add coffee:');
            if(fill === null){
                return;
            }
            if(isNaN(fill) || fill<0 || fill%1!==0){
                alert('Enter a positive integer');
                return;
            } else{
                let newCoffee = this.coffee + Number(fill);
                if(newCoffee>200){
                    this.coffee = 200;
                    machine.coffeStatus();
                } else{
                    this.coffee = newCoffee;
                    machine.coffeStatus();
                }
            }    
        }
        message('You added coffee');
    },
    emptyCoffe: function(number){
        if(number<=this.coffee){
            this.coffee -= number;
            machine.coffeStatus();
            message('Pouring coffee');
        } else{
            message('Machine is out of coffee');
            return;
        }
    },
    milkStatus: function(){
        document.getElementById('milk-status').innerText = this.milk;
    },
    addMilk: function(){
        if(this.milk >= 100){
            alert('The machine is already filled with milk');
        } else{
            let fill = prompt('Add milk:');
            if(fill === null){
                return;
            }
            if(isNaN(fill) || fill<0 || fill%1!==0){
                alert('Enter a positive integer');
                return;
            } else{
                let newMilk = this.milk + Number(fill);
                if(newMilk>100){
                    this.milk = 100;
                    machine.milkStatus();
                } else{
                    this.milk = newMilk;
                    machine.milkStatus();
                }
            }   
        }
        message('You added milk');
    },
    emptyMilk: function(number){
        if(number<=this.milk){
            this.milk -= number;
            machine.milkStatus();
            message('Pouring milk');
        } else{
            message('Machine is out of milk.');
            return;
        }
    },
    creditStatus: function(){
        document.getElementById('credit').innerText = this.credit;
    },
    addCredit: function(){
        let fill = prompt('Add credit:');
        if(fill === null){
            return;
        }
        if(isNaN(fill) || fill<0 || fill%1!==0){
            alert('Enter a positive integer');
            return;
        } else{
            let newCredit = this.credit + Number(fill);
            this.credit = newCredit;
            machine.creditStatus();
        }
        message('You added credit');
    },
    emptyCredit: function(number){
        if(number<=this.credit){
            this.credit -= number;
            machine.creditStatus();
            message('DISPLAY MESAGE');
        } else{
            message('No enough credit');
            return;
        }
    },
    makeShortEspresso: function(){
        if(this.credit<30){
            this.emptyCredit(30);
        } else if(this.water<20){
            this.emptyWater(20);
        } else if(this.coffee<10){
            this.emptyCoffe(10);
        } else{
            this.emptyCredit(30);
            message('Preparing short esspresso');
            setTimeout(this.emptyWater.bind(this),1000, 20);
            setTimeout(this.emptyCoffe.bind(this),2000, 10);
            setTimeout(function(){
                document.getElementById('message').innerText = 'Short espresso finished';
            }, 3000);
        }
    },
    makeLongEspresso: function(){
        if(this.credit<40){
            this.emptyCredit(40);
        } else if(this.water<40){
            this.emptyWater(40);
        } else if(this.coffee<10){
            this.emptyCoffe(10);
        } else{
            this.emptyCredit(40);
            message('Preparing long esspresso');
            setTimeout(this.emptyWater.bind(this),1000, 40);
            setTimeout(this.emptyCoffe.bind(this),2000, 10);
            setTimeout(function(){
                document.getElementById('message').innerText = 'Long espresso finished';
            }, 3000);
        }
    },
    makeCapuchino: function(){
        if(this.credit<50){
            this.emptyCredit(50);
        } else if(this.water<20){
            this.emptyWater(20);
        } else if(this.coffee<10){
            this.emptyCoffe(10);
        } else if(this.milk<10){
            this.emptyMilk(10);
        } else{
            this.emptyCredit(50);
            message('Preparing capuchino');
            setTimeout(this.emptyWater.bind(this),1000, 20);
            setTimeout(this.emptyCoffe.bind(this),2000, 10);
            setTimeout(this.emptyMilk.bind(this),3000, 10);
            setTimeout(function(){
                document.getElementById('message').innerText = 'Capuchino finished';
            }, 4000);
        }
    }
}


function message(text){
    document.getElementById('message').innerText = text;
}

machine.waterStatus();
machine.coffeStatus();
machine.milkStatus();
machine.creditStatus();
