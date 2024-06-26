import React from "react";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import axios from "axios";
import { toast } from "sonner";

export default function DeleteModal({ fetchData, open, setOpen, id }) {
    const token = localStorage.getItem("token");
    const handleDelete = async () => {
        try {
            const response = await axios.delete(`/api/v1/forum/delete/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            toast.success(response.data.message);
            setOpen(!open);
            fetchData();
        } catch (e) {
            toast.error(e.response.data.message);
        }
    };

    return (
        <Dialog onOpenChange={setOpen} open={open}>
            <DialogContent className=" w-[92%]">
                <DialogHeader>
                    <DialogTitle>Hapus</DialogTitle>
                    <DialogDescription>
                        <p className="text-sm md:text-base">
                            Apakah anda yakin ingin menghapus data ini?
                        </p>
                        <br />
                        <span className="text-destructive">
                            Aksi ini tidak dapat dibatalkan.
                        </span>
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline">Batal</Button>
                    <Button
                        variant="destructive"
                        className="mb-4"
                        onClick={handleDelete}
                    >
                        Hapus
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
