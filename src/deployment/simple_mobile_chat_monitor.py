#!/usr/bin/env python3
"""
Simple File-Based Mobile Chat Monitor
Monitors a local file for mobile instructions from the website
"""

import os
import json
import time
from datetime import datetime
from typing import List, Dict, Any

class SimpleMobileChatMonitor:
    def __init__(self):
        self.instructions_file = "mobile_instructions.json"
        self.log_file = "mobile_chat_log.txt"
        
    def log_message(self, message: str):
        """Log message to file with timestamp"""
        timestamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        with open(self.log_file, "a", encoding="utf-8") as f:
            f.write(f"[{timestamp}] {message}\n")
        print(f"[{timestamp}] {message}")
    
    def get_instructions(self) -> List[Dict[str, Any]]:
        """Get instructions from file"""
        if not os.path.exists(self.instructions_file):
            return []
        
        try:
            with open(self.instructions_file, "r", encoding="utf-8") as f:
                return json.load(f)
        except (json.JSONDecodeError, FileNotFoundError):
            return []
    
    def save_instructions(self, instructions: List[Dict[str, Any]]):
        """Save instructions to file"""
        with open(self.instructions_file, "w", encoding="utf-8") as f:
            json.dump(instructions, f, indent=2, ensure_ascii=False)
    
    def add_instruction(self, instruction: str, source: str = "mobile"):
        """Add new instruction"""
        instructions = self.get_instructions()
        
        new_instruction = {
            "id": len(instructions) + 1,
            "instruction": instruction,
            "timestamp": datetime.now().isoformat(),
            "source": source,
            "status": "pending"
        }
        
        instructions.append(new_instruction)
        self.save_instructions(instructions)
        self.log_message(f"📱 New instruction added: {instruction}")
        return new_instruction["id"]
    
    def mark_processed(self, instruction_id: int):
        """Mark instruction as processed"""
        instructions = self.get_instructions()
        
        for inst in instructions:
            if inst.get("id") == instruction_id:
                inst["status"] = "processed"
                inst["processed_at"] = datetime.now().isoformat()
                break
        
        self.save_instructions(instructions)
        self.log_message(f"✅ Instruction {instruction_id} marked as processed")
    
    def get_pending_instructions(self) -> List[Dict[str, Any]]:
        """Get only pending instructions"""
        instructions = self.get_instructions()
        return [inst for inst in instructions if inst.get("status") == "pending"]
    
    def process_instruction(self, instruction: Dict[str, Any]) -> bool:
        """Process a single instruction"""
        try:
            self.log_message(f"🔄 Processing: {instruction['instruction']}")
            
            # Here's where you'd integrate with your deployment system
            # For now, we'll just log it and mark as processed
            
            # Example processing steps:
            # 1. Parse the instruction
            # 2. Determine what changes to make
            # 3. Apply changes to theme files
            # 4. Deploy updates
            
            # Simulate processing time
            time.sleep(1)
            
            self.log_message(f"✅ Successfully processed: {instruction['instruction']}")
            return True
            
        except Exception as e:
            self.log_message(f"❌ Error processing instruction: {e}")
            return False
    
    def monitor_loop(self, check_interval: int = 5):
        """Main monitoring loop"""
        self.log_message("🚀 Simple Mobile Chat Monitor Started")
        self.log_message(f"📁 Monitoring file: {os.path.abspath(self.instructions_file)}")
        self.log_message(f"📝 Log file: {os.path.abspath(self.log_file)}")
        self.log_message(f"⏱️ Check interval: {check_interval} seconds")
        
        try:
            while True:
                pending = self.get_pending_instructions()
                
                if pending:
                    self.log_message(f"📬 Found {len(pending)} pending instruction(s)")
                    
                    for instruction in pending:
                        if self.process_instruction(instruction):
                            self.mark_processed(instruction["id"])
                        else:
                            self.log_message(f"⚠️ Failed to process instruction {instruction['id']}")
                
                time.sleep(check_interval)
                
        except KeyboardInterrupt:
            self.log_message("🛑 Monitor stopped by user")
        except Exception as e:
            self.log_message(f"💥 Monitor error: {e}")
    
    def show_status(self):
        """Show current status"""
        all_instructions = self.get_instructions()
        pending = self.get_pending_instructions()
        
        print(f"\n📊 Mobile Chat Status:")
        print(f"   Total instructions: {len(all_instructions)}")
        print(f"   Pending: {len(pending)}")
        print(f"   Processed: {len(all_instructions) - len(pending)}")
        
        if pending:
            print(f"\n📋 Pending Instructions:")
            for inst in pending:
                print(f"   {inst['id']}. {inst['instruction']} (from {inst['timestamp']})")

if __name__ == "__main__":
    import sys
    
    monitor = SimpleMobileChatMonitor()
    
    if len(sys.argv) > 1:
        command = sys.argv[1]
        
        if command == "--status":
            monitor.show_status()
        elif command == "--add" and len(sys.argv) > 2:
            instruction = " ".join(sys.argv[2:])
            instruction_id = monitor.add_instruction(instruction, "manual")
            print(f"✅ Added instruction {instruction_id}: {instruction}")
        elif command == "--test":
            # Add a test instruction
            test_instruction = "Change the navigation menu color to green"
            instruction_id = monitor.add_instruction(test_instruction, "test")
            print(f"🧪 Added test instruction {instruction_id}")
        else:
            print("Usage:")
            print("  python mobile_chat_monitor.py           # Start monitoring")
            print("  python mobile_chat_monitor.py --status  # Show status")
            print("  python mobile_chat_monitor.py --add <instruction>  # Add manual instruction")
            print("  python mobile_chat_monitor.py --test    # Add test instruction")
    else:
        monitor.monitor_loop()
