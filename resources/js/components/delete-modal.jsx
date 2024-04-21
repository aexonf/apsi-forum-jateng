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

export default function DeleteModal({ open, setOpen, id }) {
    const token = localStorage.getItem("token");
    const handleDelete = async (id) => {
        try {
            const response = await axios.delete(`/api/v1/forum/delete/${id}`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            toast.success("Berhasil menghapus.");
        fetchData();
        } catch (e) {
            toast.error("Ada yang salah.");
        }
    };

    return (
        <Dialog onOpenChange={setOpen} open={open}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Hapus Post</DialogTitle>
                    <DialogDescription>
                        <p className="text-xl">Apakah anda yakin ingin menghapus post ini?</p>
                        <br />
                        <span className="text-destructive">
                            Aksi ini tidak dapat dibatalkan.
                        </span>
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline">Batal</Button>
                    <Button variant="destructive">Hapus</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}