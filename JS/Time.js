//Datetime Script
class Time{
    constructor() {
        this.TimeStart = Date.now()
        this.DeltaStart = Date.now()
        this.DeltaCurrent = 0;

        this.DeltaTime = 0;
    }

    Tick(){
        this.DeltaCurrent = Date.now();
        this.DeltaTime = (this.DeltaCurrent - this.DeltaStart)/ 100;
        this.DeltaStart = this.DeltaCurrent;
    }
}

let timeObject = new Time();